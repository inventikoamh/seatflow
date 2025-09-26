<?php

namespace App\Http\Controllers;

use App\Models\Noc;
use App\Models\Sabeel;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NocController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view-noc')->only(['index', 'show']);
        $this->middleware('can:create-noc')->only(['create', 'store', 'import', 'importPreview', 'processImport']);
        $this->middleware('can:edit-noc')->only(['edit', 'update']);
        $this->middleware('can:delete-noc')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Get current default event
        $currentEvent = EventService::getCurrentEvent();
        
        if (!$currentEvent) {
            // If no default event is set, show all NOC
            $query = Noc::with(['sabeel', 'event']);
        } else {
            // Filter by current default event
            $query = Noc::with(['sabeel', 'event'])
                ->where('event_id', $currentEvent->id);
        }

        // Filter by event (if user manually selects a different event)
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by sabeel
        if ($request->filled('sabeel_id')) {
            $query->bySabeel($request->sabeel_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'alloted') {
                $query->alloted();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        // Search by sabeel code
        if ($request->filled('search')) {
            $query->whereHas('sabeel', function ($q) use ($request) {
                $q->where('sabeel_code', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort parameters
        $allowedSorts = ['created_at', 'noc_alloted_at', 'sabeel_code', 'event_name'];
        $allowedOrders = ['asc', 'desc'];
        
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        
        if (!in_array($sortOrder, $allowedOrders)) {
            $sortOrder = 'desc';
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'sabeel_code':
                $query->join('sabeels', 'noc.sabeel_id', '=', 'sabeels.id')
                      ->orderBy('sabeels.sabeel_code', $sortOrder)
                      ->select('noc.*'); // Select only noc columns
                break;
            case 'event_name':
                $query->join('events', 'noc.event_id', '=', 'events.id')
                      ->orderBy('events.name', $sortOrder)
                      ->select('noc.*'); // Select only noc columns
                break;
            case 'noc_alloted_at':
                $query->orderBy('noc_alloted_at', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
                break;
        }

        $noc = $query->paginate(20)->appends($request->query());
        
        $events = Event::active()->orderBy('name')->get();
        $sabeels = Sabeel::orderBy('sabeel_code')->get();

        return view('noc.index', compact('noc', 'events', 'sabeels', 'sortBy', 'sortOrder', 'currentEvent'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sabeels = Sabeel::orderBy('sabeel_code')->get();
        $events = Event::active()->orderBy('name')->get();
        $currentEvent = EventService::getCurrentEvent();

        return view('noc.create', compact('sabeels', 'events', 'currentEvent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'sabeel_id' => 'required|exists:sabeels,id',
                'event_id' => 'required|exists:events,id',
                'remark' => 'nullable|string|max:1000',
            ]);

            // Check for unique sabeel-event combination
            $existingNoc = Noc::where('sabeel_id', $request->sabeel_id)
                ->where('event_id', $request->event_id)
                ->first();

            if ($existingNoc) {
                return back()->withErrors([
                    'event_id' => 'An NOC already exists for this sabeel and event combination.'
                ])->withInput();
            }

            $noc = Noc::create([
                'sabeel_id' => $request->sabeel_id,
                'event_id' => $request->event_id,
                'remark' => $request->remark,
                'noc_alloted_at' => now(), // Auto-allocate NOC when created
            ]);

            return redirect()->route('noc.show', $noc)
                ->with('success', 'NOC created and allocated successfully.');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error creating NOC: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Noc $noc): View
    {
        $noc->load(['sabeel', 'event']);
        
        return view('noc.show', compact('noc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Noc $noc): View
    {
        $sabeels = Sabeel::orderBy('sabeel_code')->get();
        $events = Event::active()->orderBy('name')->get();
        $currentEvent = EventService::getCurrentEvent();

        return view('noc.edit', compact('noc', 'sabeels', 'events', 'currentEvent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Noc $noc): RedirectResponse
    {
        $request->validate([
            'sabeel_id' => 'required|exists:sabeels,id',
            'event_id' => 'required|exists:events,id',
            'remark' => 'nullable|string|max:1000',
        ]);

        // Check for unique sabeel-event combination (excluding current record)
        $existingNoc = Noc::where('sabeel_id', $request->sabeel_id)
            ->where('event_id', $request->event_id)
            ->where('id', '!=', $noc->id)
            ->first();

        if ($existingNoc) {
            return back()->withErrors([
                'event_id' => 'An NOC already exists for this sabeel and event combination.'
            ])->withInput();
        }

        $noc->update([
            'sabeel_id' => $request->sabeel_id,
            'event_id' => $request->event_id,
            'remark' => $request->remark,
        ]);

        return redirect()->route('noc.show', $noc)
            ->with('success', 'NOC updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Noc $noc): RedirectResponse
    {
        $noc->delete();

        return redirect()->route('noc.index')
            ->with('success', 'NOC deleted successfully.');
    }

    /**
     * Allocate NOC (set noc_alloted_at timestamp).
     */
    public function allocate(Noc $noc): RedirectResponse
    {
        if ($noc->noc_alloted_at) {
            return back()->with('error', 'NOC is already allocated.');
        }

        $noc->update(['noc_alloted_at' => now()]);

        return back()->with('success', 'NOC allocated successfully.');
    }

    /**
     * Revoke NOC (remove noc_alloted_at timestamp).
     */
    public function revoke(Noc $noc): RedirectResponse
    {
        if (!$noc->noc_alloted_at) {
            return back()->with('error', 'NOC is not allocated yet.');
        }

        $noc->update(['noc_alloted_at' => null]);

        return back()->with('success', 'NOC revoked successfully.');
    }

    /**
     * Show the import form.
     */
    public function import(): View
    {
        $events = Event::active()->orderBy('name')->get();
        $currentEvent = EventService::getCurrentEvent();
        
        return view('noc.import', compact('events', 'currentEvent'));
    }

    /**
     * Download sample CSV file.
     */
    public function sample(): BinaryFileResponse
    {
        $filename = 'noc_sample.csv';
        $filepath = storage_path('app/samples/' . $filename);
        
        // Create samples directory if it doesn't exist
        if (!file_exists(storage_path('app/samples'))) {
            mkdir(storage_path('app/samples'), 0755, true);
        }
        
        // Create sample CSV content
        $sampleData = [
            ['sabeel_code'],
            ['2163'],
            ['2164'],
            ['2165'],
        ];
        
        $file = fopen($filepath, 'w');
        foreach ($sampleData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
        
        return response()->download($filepath, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Process uploaded CSV and show preview.
     */
    public function importPreview(Request $request): View|RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'event_id' => 'required|exists:events,id',
        ]);

        try {
            $file = $request->file('csv_file');
            $eventId = $request->event_id;
            
            // Store file temporarily
            $filename = 'noc_import_' . time() . '.csv';
            $filepath = $file->storeAs('temp', $filename);
            
            // Read CSV file
            $csvData = [];
            $handle = fopen(storage_path('app/' . $filepath), 'r');
            
            if ($handle !== false) {
                $header = fgetcsv($handle); // Skip header
                
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) >= 1) {
                        $csvData[] = [
                            'sabeel_code' => trim($row[0]),
                        ];
                    }
                }
                fclose($handle);
            }
            
            // Validate each row
            $validatedData = [];
            $errors = [];
            
            foreach ($csvData as $index => $row) {
                $validator = Validator::make($row, [
                    'sabeel_code' => 'required|string',
                ]);
                
                if ($validator->fails()) {
                    $errors[$index + 2] = $validator->errors()->all(); // +2 because we skipped header and array is 0-indexed
                } else {
                    // Check if sabeel exists
                    $sabeel = Sabeel::where('sabeel_code', $row['sabeel_code'])->first();
                    if (!$sabeel) {
                        $errors[$index + 2] = ['Sabeel code not found'];
                    } else {
                        // Check if NOC already exists for this sabeel and event
                        $existingNoc = Noc::where('sabeel_id', $sabeel->id)
                            ->where('event_id', $eventId)
                            ->first();
                        
                        if ($existingNoc) {
                            $errors[$index + 2] = ['NOC already exists for this sabeel and event'];
                        } else {
                            $validatedData[] = [
                                'sabeel_code' => $row['sabeel_code'],
                                'sabeel_id' => $sabeel->id,
                                'sabeel_hof' => $sabeel->sabeel_hof,
                            ];
                        }
                    }
                }
            }
            
            $event = Event::find($eventId);
            
            return view('noc.import-preview', compact(
                'validatedData', 
                'errors', 
                'event', 
                'filename',
                'csvData'
            ));
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error processing CSV file: ' . $e->getMessage()]);
        }
    }

    /**
     * Process the import after preview confirmation.
     */
    public function processImport(Request $request): RedirectResponse
    {
        $request->validate([
            'filename' => 'required|string',
            'event_id' => 'required|exists:events,id',
            'validated_data' => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            
            $successful = 0;
            $failed = 0;
            $errors = [];
            
            foreach ($request->validated_data as $index => $data) {
                try {
                    Noc::create([
                        'sabeel_id' => $data['sabeel_id'],
                        'event_id' => $request->event_id,
                        'remark' => 'Imported from CSV',
                        'noc_alloted_at' => now(), // Auto-allocate NOC when imported
                    ]);
                    $successful++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[$index] = [$e->getMessage()];
                }
            }
            
            DB::commit();
            
            // Clean up temp file
            Storage::delete('temp/' . $request->filename);
            
            $message = "Import completed successfully! {$successful} NOC records imported.";
            if ($failed > 0) {
                $message .= " {$failed} records failed.";
            }
            
            return redirect()->route('noc.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up temp file
            Storage::delete('temp/' . $request->filename);
            
            return back()->withErrors(['error' => 'Error processing import: ' . $e->getMessage()]);
        }
    }
}