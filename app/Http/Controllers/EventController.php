<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view-events')->only(['index', 'show']);
        $this->middleware('can:create-events')->only(['create', 'store']);
        $this->middleware('can:edit-events')->only(['edit', 'update']);
        $this->middleware('can:delete-events')->only(['destroy']);
        $this->middleware('can:manage-events')->only(['setDefault']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Filter by event type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $events = $query->with('previousEvent')->orderBy('start_date', 'desc')->paginate(15);

        return view('events.index', [
            'events' => $events,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Events']
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ramzaanEvents = Event::where('event_type', 'ramzaan')->where('is_active', true)->orderBy('start_date', 'desc')->get();
        $asharaEvents = Event::where('event_type', 'ashara')->where('is_active', true)->orderBy('start_date', 'desc')->get();

        return view('events.create', [
            'ramzaanEvents' => $ramzaanEvents,
            'asharaEvents' => $asharaEvents,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Events', 'url' => route('events.index')],
                ['title' => 'Create Event']
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:events',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'event_type' => 'required|in:ramzaan,ashara',
            'previous_event_id' => 'nullable|exists:events,id',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Validate previous event type matches current event type
        if ($request->previous_event_id) {
            $previousEvent = Event::find($request->previous_event_id);
            if ($previousEvent && $previousEvent->event_type !== $request->event_type) {
                return back()->withErrors(['previous_event_id' => 'Previous event must be of the same type (Ramzaan or Ashara).']);
            }
        }

        $event = Event::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'event_type' => $request->event_type,
            'previous_event_id' => $request->previous_event_id,
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // If this event is set as default, unset others of the same type
        if ($event->is_default) {
            $event->setAsDefault();
        }

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['previousEvent', 'nextEvents']);
        
        return view('events.show', [
            'event' => $event,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Events', 'url' => route('events.index')],
                ['title' => $event->name]
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $availablePreviousEvents = $event->getAvailablePreviousEvents();

        return view('events.edit', [
            'event' => $event,
            'availablePreviousEvents' => $availablePreviousEvents,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('dashboard')],
                ['title' => 'Events', 'url' => route('events.index')],
                ['title' => 'Edit ' . $event->name]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:events,name,' . $event->id,
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'event_type' => 'required|in:ramzaan,ashara',
            'previous_event_id' => 'nullable|exists:events,id',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Validate previous event type matches current event type
        if ($request->previous_event_id) {
            $previousEvent = Event::find($request->previous_event_id);
            if ($previousEvent && $previousEvent->event_type !== $request->event_type) {
                return back()->withErrors(['previous_event_id' => 'Previous event must be of the same type (Ramzaan or Ashara).']);
            }
        }

        $event->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'event_type' => $request->event_type,
            'previous_event_id' => $request->previous_event_id,
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // If this event is set as default, unset others of the same type
        if ($event->is_default) {
            $event->setAsDefault();
        }

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Check if event has seat maps
        if ($event->seatMaps()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete event with existing seat allocations. Please remove seat allocations first.']);
        }

        // Check if this event is referenced as previous event
        if ($event->nextEvents()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete event that is referenced as previous event by other events.']);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Set an event as default.
     */
    public function setDefault(Event $event)
    {
        $event->setAsDefault();

        return redirect()->route('events.index')
            ->with('success', "{$event->name} has been set as the default {$event->event_type} event.");
    }
}
