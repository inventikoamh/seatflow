<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Seat;
use Illuminate\Http\Request;

class SeatMapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view-locations')->only(['show', 'getSeatMap']);
    }

    /**
     * Show the seat map page
     */
    public function show($areaId)
    {
        $area = Area::with('location')->findOrFail($areaId);
        
        $breadcrumbs = [
            ['title' => 'Locations', 'url' => route('locations.index')],
            ['title' => $area->location->name, 'url' => route('locations.show', $area->location)],
            ['title' => 'Seat Map', 'url' => null],
        ];

        return view('seat-maps.show', compact('area', 'breadcrumbs'));
    }

    /**
     * Get seat map data for an area
     */
    public function getSeatMap(Request $request, $areaId)
    {
        $area = Area::with('location')->findOrFail($areaId);
        
        // Get seats for this area
        $seats = Seat::where('area_id', $areaId)
            ->active()
            ->orderBy('row_number')
            ->orderBy('column_number')
            ->get();

        // Transform seats for frontend
        $seatData = $seats->map(function ($seat) {
            return [
                'id' => $seat->id,
                'seatNumber' => $seat->seat_number,
                'row' => $seat->row_number,
                'column' => $seat->column_number,
                'columnLabel' => $seat->column_label,
                'position' => $seat->position,
                'isSelectable' => $seat->isSelectable(),
            ];
        });

        // Get grid dimensions
        $maxRow = $seats->max('row_number');
        $maxColumn = $seats->max('column_number');

        return response()->json([
            'area' => [
                'id' => $area->id,
                'name' => $area->name,
                'description' => $area->description,
                'capacity' => $area->capacity,
                'gender_type' => $area->gender_type,
                'floor' => $area->floor,
                'section' => $area->section,
                'event_type' => $area->event_type,
            ],
            'location' => [
                'id' => $area->location->id,
                'name' => $area->location->name,
                'slug' => $area->location->slug,
            ],
            'grid' => [
                'maxRow' => $maxRow,
                'maxColumn' => $maxColumn,
                'totalSeats' => $seats->count(),
            ],
            'seats' => $seatData,
        ]);
    }


    /**
     * Get seat statistics for an area
     */
    public function getSeatStats($areaId)
    {
        $stats = Seat::where('area_id', $areaId)
            ->active()
            ->selectRaw('COUNT(*) as total_seats')
            ->first();

        return response()->json($stats);
    }
}