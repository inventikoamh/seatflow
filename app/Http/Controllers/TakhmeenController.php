<?php

namespace App\Http\Controllers;

use App\Models\Takhmeen;
use App\Models\Sabeel;
use App\Models\Event;
use App\Models\TakhmeenImportBatch;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TakhmeenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view-takhmeen')->only(['index', 'show']);
        $this->middleware('can:create-takhmeen')->only(['create', 'store', 'import', 'importPreview', 'processImport']);
        $this->middleware('can:edit-takhmeen')->only(['edit', 'update']);
        $this->middleware('can:delete-takhmeen')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Get current default event
        $currentEvent = EventService::getCurrentEvent();
        
        if (!$currentEvent) {
            // If no default event is set, show all takhmeen
            $query = Takhmeen::with(['sabeel', 'event']);
        } else {
            // Filter by current default event
            $query = Takhmeen::with(['sabeel', 'event'])
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
        $allowedSorts = ['created_at', 'amount', 'sabeel_code', 'event_name'];
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
                $query->join('sabeels', 'takhmeen.sabeel_id', '=', 'sabeels.id')
                      ->orderBy('sabeels.sabeel_code', $sortOrder)
                      ->select('takhmeen.*'); // Select only takhmeen columns
                break;
            case 'event_name':
                $query->join('events', 'takhmeen.event_id', '=', 'events.id')
                      ->orderBy('events.name', $sortOrder)
                      ->select('takhmeen.*'); // Select only takhmeen columns
                break;
            case 'amount':
                $query->orderBy('amount', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
                break;
        }

        $takhmeen = $query->paginate(20)->appends($request->query());
        
        $events = Event::active()->orderBy('name')->get();
        $sabeels = Sabeel::orderBy('sabeel_code')->get();

        return view('takhmeen.index', compact('takhmeen', 'events', 'sabeels', 'sortBy', 'sortOrder', 'currentEvent'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sabeels = Sabeel::orderBy('sabeel_code')->get();
        $events = Event::active()->orderBy('name')->get();
        $currentEvent = EventService::getCurrentEvent();

        return view('takhmeen.create', compact('sabeels', 'events', 'currentEvent'));
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
                'amount' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:1000',
                'hof_photo' => 'nullable|string|max:500',
            ]);

            // Check for unique sabeel-event combination
            $existingTakhmeen = Takhmeen::where('sabeel_id', $request->sabeel_id)
                ->where('event_id', $request->event_id)
                ->first();

            if ($existingTakhmeen) {
                return back()->withErrors([
                    'event_id' => 'A takhmeen already exists for this sabeel and event combination.'
                ])->withInput();
            }

            $takhmeen = Takhmeen::create([
                'sabeel_id' => $request->sabeel_id,
                'event_id' => $request->event_id,
                'amount' => $request->amount,
                'notes' => $request->notes,
                'hof_photo' => $request->hof_photo,
            ]);

            return redirect()->route('takhmeen.show', $takhmeen)
                ->with('success', 'Takhmeen created successfully.');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error creating takhmeen: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Takhmeen $takhmeen): View
    {
        $takhmeen->load(['sabeel', 'event']);
        
        return view('takhmeen.show', compact('takhmeen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Takhmeen $takhmeen): View
    {
        $sabeels = Sabeel::orderBy('sabeel_code')->get();
        $events = Event::active()->orderBy('name')->get();
        $currentEvent = EventService::getCurrentEvent();

        return view('takhmeen.edit', compact('takhmeen', 'sabeels', 'events', 'currentEvent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Takhmeen $takhmeen): RedirectResponse
    {
        $request->validate([
            'sabeel_id' => 'required|exists:sabeels,id',
            'event_id' => 'required|exists:events,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'hof_photo' => 'nullable|string|max:500',
        ]);

        // Check for unique sabeel-event combination (excluding current record)
        $existingTakhmeen = Takhmeen::where('sabeel_id', $request->sabeel_id)
            ->where('event_id', $request->event_id)
            ->where('id', '!=', $takhmeen->id)
            ->first();

        if ($existingTakhmeen) {
            return back()->withErrors([
                'event_id' => 'A takhmeen already exists for this sabeel and event combination.'
            ])->withInput();
        }

        $takhmeen->update([
            'sabeel_id' => $request->sabeel_id,
            'event_id' => $request->event_id,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'hof_photo' => $request->hof_photo,
        ]);

        return redirect()->route('takhmeen.show', $takhmeen)
            ->with('success', 'Takhmeen updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Takhmeen $takhmeen): RedirectResponse
    {
        $takhmeen->delete();

        return redirect()->route('takhmeen.index')
            ->with('success', 'Takhmeen deleted successfully.');
    }

    /**
     * Show the import form.
     */
    public function import(): View
    {
        $events = Event::active()->orderBy('name')->get();
        $currentEvent = EventService::getCurrentEvent();
        
        return view('takhmeen.import', compact('events', 'currentEvent'));
    }

    /**
     * Download sample CSV file.
     */
    public function sample(): BinaryFileResponse
    {
        $filename = 'takhmeen_sample.csv';
        $filepath = storage_path('app/samples/' . $filename);
        
        // Create samples directory if it doesn't exist
        if (!file_exists(storage_path('app/samples'))) {
            mkdir(storage_path('app/samples'), 0755, true);
        }
        
        // Create sample CSV content
        $sampleData = [
            ['sabeel_code', 'amount'],
            ['2163', '5000.00'],
            ['2164', '7500.00'],
            ['2165', '3000.00'],
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
            $filename = 'takhmeen_import_' . time() . '.csv';
            $filepath = $file->storeAs('temp', $filename);
            
            // Read CSV file
            $csvData = [];
            $handle = fopen(storage_path('app/' . $filepath), 'r');
            
            if ($handle !== false) {
                $header = fgetcsv($handle); // Skip header
                
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) >= 2) {
                        $csvData[] = [
                            'sabeel_code' => trim($row[0]),
                            'amount' => trim($row[1]),
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
                    'amount' => 'required|numeric|min:0',
                ]);
                
                if ($validator->fails()) {
                    $errors[$index + 2] = $validator->errors()->all(); // +2 because we skipped header and array is 0-indexed
                } else {
                    // Check if sabeel exists
                    $sabeel = Sabeel::where('sabeel_code', $row['sabeel_code'])->first();
                    if (!$sabeel) {
                        $errors[$index + 2] = ['Sabeel code not found'];
                    } else {
                        // Check if takhmeen already exists for this sabeel and event
                        $existingTakhmeen = Takhmeen::where('sabeel_id', $sabeel->id)
                            ->where('event_id', $eventId)
                            ->first();
                        
                        if ($existingTakhmeen) {
                            $errors[$index + 2] = ['Takhmeen already exists for this sabeel and event'];
                        } else {
                            $validatedData[] = [
                                'sabeel_code' => $row['sabeel_code'],
                                'amount' => $row['amount'],
                                'formatted_amount' => '₹' . $this->formatIndianNumber($row['amount']),
                                'sabeel_id' => $sabeel->id,
                                'sabeel_hof' => $sabeel->sabeel_hof,
                            ];
                        }
                    }
                }
            }
            
            $event = Event::find($eventId);
            
            return view('takhmeen.import-preview', compact(
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
            
            // Create import batch record
            $batch = TakhmeenImportBatch::create([
                'event_id' => $request->event_id,
                'filename' => $request->filename,
                'total_records' => count($request->validated_data),
                'successful_imports' => 0,
                'failed_imports' => 0,
                'status' => 'processing',
                'imported_by' => auth()->id(),
            ]);
            
            $successful = 0;
            $failed = 0;
            $errors = [];
            
            foreach ($request->validated_data as $index => $data) {
                try {
                    Takhmeen::create([
                        'sabeel_id' => $data['sabeel_id'],
                        'event_id' => $request->event_id,
                        'amount' => $data['amount'],
                        'notes' => 'Imported from CSV',
                        'import_batch_id' => $batch->id,
                    ]);
                    $successful++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[$index] = [$e->getMessage()];
                }
            }
            
            // Update batch status
            $batch->update([
                'successful_imports' => $successful,
                'failed_imports' => $failed,
                'errors' => $errors,
                'status' => $failed > 0 ? 'failed' : 'completed',
            ]);
            
            DB::commit();
            
            // Clean up temp file
            Storage::delete('temp/' . $request->filename);
            
            $message = "Import completed successfully! {$successful} records imported.";
            if ($failed > 0) {
                $message .= " {$failed} records failed.";
            }
            
            return redirect()->route('takhmeen.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up temp file
            Storage::delete('temp/' . $request->filename);
            
            return back()->withErrors(['error' => 'Error processing import: ' . $e->getMessage()]);
        }
    }

    /**
     * Format number in Indian numbering system.
     */
    private function formatIndianNumber($number): string
    {
        $number = (float) $number;
        $integerPart = floor($number);
        $decimalPart = $number - $integerPart;
        
        // Convert to string and reverse for easier processing
        $str = strrev((string) $integerPart);
        $length = strlen($str);
        
        $result = '';
        
        for ($i = 0; $i < $length; $i++) {
            if ($i == 3) {
                // First comma after 3 digits (thousands)
                $result .= ',';
            } elseif ($i > 3 && ($i - 3) % 2 == 0) {
                // Then every 2 digits (lakhs, crores, etc.)
                $result .= ',';
            }
            $result .= $str[$i];
        }
        
        // Reverse back and add decimal part
        $formatted = strrev($result);
        
        if ($decimalPart > 0) {
            $formatted .= '.' . sprintf('%02d', round($decimalPart * 100));
        } else {
            $formatted .= '.00';
        }
        
        return $formatted;
    }
}