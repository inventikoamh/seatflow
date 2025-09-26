<?php

namespace App\Http\Controllers;

use App\Models\Mumin;
use App\Models\Sabeel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MuminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view-mumineen')->only(['index', 'show']);
        $this->middleware('can:create-mumineen')->only(['create', 'store']);
        $this->middleware('can:edit-mumineen')->only(['edit', 'update']);
        $this->middleware('can:delete-mumineen')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mumin::with('sabeel');

        // Filter out mumineen from left sabeels by default unless explicitly requested
        if (!$request->filled('include_left_sabeels') || $request->include_left_sabeels !== 'yes') {
            $query->whereHas('sabeel', function ($q) {
                $q->where('sabeel_type', '!=', 'left_sabeel');
            });
        }

        // Apply filters
        if ($request->filled('sabeel_code')) {
            $sabeelCode = trim($request->sabeel_code);
            $query->where('sabeel_code', $sabeelCode);
        }

        if ($request->filled('sector')) {
            $query->whereHas('sabeel', function ($q) use ($request) {
                $q->where('sabeel_sector', $request->sector);
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('misaq')) {
            $query->where('misaq', $request->misaq);
        }

        if ($request->filled('age_min')) {
            $query->where('age', '>=', $request->age_min);
        }

        if ($request->filled('age_max')) {
            $query->where('age', '<=', $request->age_max);
        }

        if ($request->filled('has_mobile')) {
            if ($request->has_mobile === 'yes') {
                $query->whereNotNull('mobile_number');
            } else {
                $query->whereNull('mobile_number');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ITS_ID', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        $mumineen = $query->orderBy('full_name')->paginate(15);

        // Check if sabeel filter was applied but no results found
        $sabeelNotFound = false;
        if ($request->filled('sabeel_code')) {
            $sabeelCode = trim($request->sabeel_code);
            $sabeelExists = Sabeel::where('sabeel_code', $sabeelCode)->exists();
            if (!$sabeelExists) {
                $sabeelNotFound = true;
            }
        }

        // Get statistics
        $statistics = [
            'total_mumineen' => Mumin::count(),
            'with_mobile' => Mumin::whereNotNull('mobile_number')->count(),
            'without_mobile' => Mumin::whereNull('mobile_number')->count(),
            'head_of_family_count' => Mumin::whereIn('ITS_ID', Sabeel::whereNotNull('sabeel_hof')->pluck('sabeel_hof'))->count()
        ];

        $sabeels = Sabeel::orderBy('sabeel_code')->get();

        return view('mumineen.index', compact('mumineen', 'statistics', 'sabeels', 'sabeelNotFound'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sabeels = Sabeel::where('sabeel_type', '!=', 'left_sabeel')
                         ->orderBy('sabeel_code')->get();
        return view('mumineen.create', compact('sabeels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ITS_ID' => 'required|string|size:8|unique:mumineen,ITS_ID',
            'full_name' => 'required|string|max:255',
            'sabeel_code' => 'required|exists:sabeels,sabeel_code',
            'mobile_number' => 'nullable|string|max:15',
            'age' => 'nullable|integer|min:0|max:120',
            'gender' => 'required|in:M,F',
            'type' => 'required|in:mehmaan,resident',
            'misaq' => 'required|in:done,not_done'
        ]);

        Mumin::create([
            'ITS_ID' => $request->ITS_ID,
            'full_name' => $request->full_name,
            'sabeel_code' => $request->sabeel_code,
            'mobile_number' => $request->mobile_number,
            'age' => $request->age,
            'gender' => $request->gender,
            'type' => $request->type,
            'misaq' => $request->misaq
        ]);

        return redirect()->route('mumineen.index')
            ->with('success', 'Mumin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mumin $mumin)
    {
        $mumin->load('sabeel');
        $headOfFamilySabeels = $mumin->headOfFamilySabeels();
        return view('mumineen.show', compact('mumin', 'headOfFamilySabeels'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mumin $mumin)
    {
        $sabeels = Sabeel::where('sabeel_type', '!=', 'left_sabeel')
                         ->orderBy('sabeel_code')->get();
        return view('mumineen.edit', compact('mumin', 'sabeels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mumin $mumin)
    {
        $request->validate([
            'ITS_ID' => [
                'required',
                'string',
                'size:8',
                Rule::unique('mumineen')->ignore($mumin->id)
            ],
            'full_name' => 'required|string|max:255',
            'sabeel_code' => 'required|exists:sabeels,sabeel_code',
            'mobile_number' => 'nullable|string|max:15',
            'age' => 'nullable|integer|min:0|max:120',
            'gender' => 'required|in:M,F',
            'type' => 'required|in:mehmaan,resident',
            'misaq' => 'required|in:done,not_done'
        ]);

        $mumin->update([
            'ITS_ID' => $request->ITS_ID,
            'full_name' => $request->full_name,
            'sabeel_code' => $request->sabeel_code,
            'mobile_number' => $request->mobile_number,
            'age' => $request->age,
            'gender' => $request->gender,
            'type' => $request->type,
            'misaq' => $request->misaq
        ]);

        return redirect()->route('mumineen.index')
            ->with('success', 'Mumin updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mumin $mumin)
    {
        // Check if mumin is head of family for any sabeel
        if ($mumin->isHeadOfFamily()) {
            return redirect()->route('mumineen.index')
                ->with('error', 'Cannot delete mumin who is head of family. Please reassign head of family first.');
        }

        $mumin->delete();

        return redirect()->route('mumineen.index')
            ->with('success', 'Mumin deleted successfully.');
    }

    /**
     * Download sample file for import.
     */
    public function sample()
    {
        // Serve static CSV file
        $filePath = public_path('mumineen_sample.csv');
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Sample file not found.');
        }
        
        return response()->download($filePath, 'mumineen_sample.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Handle file import and show preview.
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv|max:51200' // Increased to 50MB for large files
        ]);

        try {
            $file = $request->file('import_file');
            
            // Handle CSV files
            $data = [];
            $handle = fopen($file->getPathname(), 'r');
            $headers = fgetcsv($handle);

            // Read all data (no preview limit for large imports)
            while (($row = fgetcsv($handle)) !== false) {
                $data[] = array_combine($headers, $row);
            }
            fclose($handle);

            // Validate required columns
            $requiredColumns = ['ITS_ID', 'full_name', 'sabeel_code', 'mobile_number', 'age', 'gender', 'type', 'misaq'];
            $headers = array_keys($data[0] ?? []);

            foreach ($requiredColumns as $column) {
                if (!in_array($column, $headers)) {
                    return redirect()->back()->with('error', "Missing required column: {$column}");
                }
            }

            // Process data and check for existing mumineen
            $processedData = [];
            $existingMumineen = [];
            $invalidSabeels = [];

            // Pre-load all sabeels for better performance
            $sabeelCodes = array_unique(array_column($data, 'sabeel_code'));
            $existingSabeels = Sabeel::whereIn('sabeel_code', $sabeelCodes)->pluck('sabeel_code')->toArray();

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 because of header row and 0-based index

                // Normalize type values (handle case variations)
                $normalizedRow = $row;
                if (isset($row['type'])) {
                    $typeValue = strtolower(trim($row['type']));
                    if ($typeValue === 'regular') {
                        $normalizedRow['type'] = 'resident';
                    } elseif ($typeValue === 'mehmaan') {
                        $normalizedRow['type'] = 'mehmaan';
                    }
                }

                // Validate row data
                $validation = validator($normalizedRow, [
                    'ITS_ID' => 'required|string|size:8',
                    'full_name' => 'required|string|max:255',
                    'sabeel_code' => 'required|string|max:255',
                    'mobile_number' => 'nullable|string|max:15',
                    'age' => 'nullable|integer|min:0|max:120',
                    'gender' => 'required|in:M,F',
                    'type' => 'required|in:mehmaan,resident',
                    'misaq' => 'required|in:done,not_done'
                ]);

                if ($validation->fails()) {
                    return redirect()->back()->with('error', "Validation error in row {$rowNumber}: " . implode(', ', $validation->errors()->all()));
                }

                // Check if sabeel exists (using pre-loaded data)
                if (!in_array($row['sabeel_code'], $existingSabeels)) {
                    $invalidSabeels[] = [
                        'row_number' => $rowNumber,
                        'sabeel_code' => $row['sabeel_code'],
                        'mumin_name' => $row['full_name']
                    ];
                    continue; // Skip this row
                }

                $processedData[] = [
                    'row_number' => $rowNumber,
                    'ITS_ID' => $normalizedRow['ITS_ID'],
                    'full_name' => $normalizedRow['full_name'],
                    'sabeel_code' => $normalizedRow['sabeel_code'],
                    'mobile_number' => $normalizedRow['mobile_number'],
                    'age' => $normalizedRow['age'],
                    'gender' => $normalizedRow['gender'],
                    'type' => $normalizedRow['type'],
                    'misaq' => $normalizedRow['misaq']
                ];

                // Check if mumin already exists (will be done in bulk later for performance)
                // We'll check this during processing to avoid N+1 queries
            }

            // Check for existing mumineen in bulk for better performance
            $itsIds = array_column($processedData, 'ITS_ID');
            $existingMumineenData = Mumin::whereIn('ITS_ID', $itsIds)->get()->keyBy('ITS_ID');
            
            foreach ($processedData as $data) {
                if ($existingMumineenData->has($data['ITS_ID'])) {
                    $existingMumineen[] = [
                        'row_number' => $data['row_number'],
                        'ITS_ID' => $data['ITS_ID'],
                        'existing_data' => $existingMumineenData->get($data['ITS_ID']),
                        'new_data' => $data
                    ];
                }
            }

            // Store data in session for processing
            session([
                'import_data' => $processedData, 
                'existing_mumineen' => $existingMumineen,
                'invalid_sabeels' => $invalidSabeels
            ]);

            // Debug info
            $totalRecords = count($data);
            $processedRecords = count($processedData);
            $existingRecords = count($existingMumineen);
            $newRecords = $processedRecords - $existingRecords;

            return redirect()->route('mumineen.import.preview')->with('info', 
                "File processed: {$totalRecords} total rows, {$processedRecords} valid records ({$existingRecords} existing, {$newRecords} new)"
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error reading file: ' . $e->getMessage());
        }
    }

    /**
     * Show import preview with overwrite/skip options.
     */
    public function importPreview()
    {
        $importData = session('import_data', []);
        $existingMumineen = session('existing_mumineen', []);
        $invalidSabeels = session('invalid_sabeels', []);
        
        if (empty($importData)) {
            return redirect()->route('mumineen.index')->with('error', 'No import data found.');
        }

        return view('mumineen.import-preview', compact('importData', 'existingMumineen', 'invalidSabeels'));
    }

    /**
     * Process the import with user selections.
     */
    public function processImport(Request $request)
    {
        $importData = session('import_data', []);
        $existingMumineen = session('existing_mumineen', []);
        
        if (empty($importData)) {
            return redirect()->route('mumineen.index')->with('error', 'No import data found.');
        }

        // Check if we need to process in chunks due to max_input_vars limit
        $totalRecords = count($importData);
        $maxInputVars = ini_get('max_input_vars');
        
        if ($totalRecords > ($maxInputVars * 0.8)) { // Use 80% of limit to be safe
            return $this->processMuminImportInChunks($request, $importData, $existingMumineen);
        }

        $request->validate([
            'action.*' => 'required|in:skip,overwrite,create'
        ]);

        $imported = 0;
        $skipped = 0;
        $overwritten = 0;
        $errors = [];

        // Process in chunks for better performance with database transactions
        $chunks = array_chunk($importData, 1000); // Increased chunk size for better performance
        
        foreach ($chunks as $chunkIndex => $chunk) {
            \DB::transaction(function () use ($chunk, $request, &$imported, &$skipped, &$overwritten, &$errors) {
                // Pre-load existing mumineen for this chunk to avoid N+1 queries
                $itsIds = array_column($chunk, 'ITS_ID');
                $existingMumineen = Mumin::whereIn('ITS_ID', $itsIds)->get()->keyBy('ITS_ID');
                
                foreach ($chunk as $data) {
                    $itsId = $data['ITS_ID'];
                    $action = $request->input("action.{$itsId}", 'skip');
                    
                    try {
                        if ($action === 'skip') {
                            $skipped++;
                            continue;
                        }
                        
                        if ($action === 'overwrite') {
                            // Update existing mumin
                            if ($existingMumineen->has($itsId)) {
                                $existingMumin = $existingMumineen->get($itsId);
                                $existingMumin->update([
                                    'full_name' => $data['full_name'],
                                    'sabeel_code' => $data['sabeel_code'],
                                    'mobile_number' => $data['mobile_number'],
                                    'age' => $data['age'],
                                    'gender' => $data['gender'],
                                    'type' => $data['type'],
                                    'misaq' => $data['misaq']
                                ]);
                                $overwritten++;
                            } else {
                                // Create new mumin if it doesn't exist
                                Mumin::create($data);
                                $imported++;
                            }
                        } elseif ($action === 'create') {
                            // Create new mumin
                            Mumin::create($data);
                            $imported++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Error processing mumin {$itsId}: " . $e->getMessage();
                    }
                }
            });
            
            // Add a small delay between chunks to prevent overwhelming the database
            if ($chunkIndex < count($chunks) - 1) {
                usleep(100000); // 0.1 second delay
            }
        }

        // Clear session data
        session()->forget(['import_data', 'existing_mumineen', 'invalid_sabeels']);

        $totalRecords = count($importData);
        $message = "Import completed. Total records: {$totalRecords}, Imported: {$imported}, Overwritten: {$overwritten}, Skipped: {$skipped}";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode('; ', array_slice($errors, 0, 5)); // Show first 5 errors
            if (count($errors) > 5) {
                $message .= " and " . (count($errors) - 5) . " more errors.";
            }
        }

        return redirect()->route('mumineen.index')->with('success', $message);
    }

    /**
     * Process mumin import in chunks to handle large datasets.
     */
    private function processMuminImportInChunks(Request $request, $importData, $existingMumineen)
    {
        $chunkSize = 800; // Process 800 records at a time (safe limit)
        $chunks = array_chunk($importData, $chunkSize);
        
        $totalImported = 0;
        $totalSkipped = 0;
        $totalOverwritten = 0;
        $allErrors = [];
        
        foreach ($chunks as $chunkIndex => $chunk) {
            $imported = 0;
            $skipped = 0;
            $overwritten = 0;
            $errors = [];
            
            // Process each chunk
            foreach ($chunk as $data) {
                $itsId = $data['ITS_ID'];
                
                try {
                    // For large imports, assume all new records should be created
                    // and all existing records should be skipped (to avoid form input limits)
                    $existingMumin = Mumin::where('ITS_ID', $itsId)->first();
                    
                    if ($existingMumin) {
                        // Skip existing mumineen in large imports
                        $skipped++;
                    } else {
                        // Create new mumineen
                        Mumin::create($data);
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error processing mumin {$itsId}: " . $e->getMessage();
                }
            }
            
            $totalImported += $imported;
            $totalSkipped += $skipped;
            $totalOverwritten += $overwritten;
            $allErrors = array_merge($allErrors, $errors);
            
            // Small delay between chunks to prevent overwhelming the database
            if ($chunkIndex < count($chunks) - 1) {
                usleep(100000); // 0.1 second delay
            }
        }

        // Clear session data
        session()->forget(['import_data', 'existing_mumineen', 'invalid_sabeels']);

        $totalRecords = count($importData);
        $message = "Large mumin import completed. Total records: {$totalRecords}, Imported: {$totalImported}, Skipped: {$totalSkipped}, Overwritten: {$totalOverwritten}";
        if (!empty($allErrors)) {
            $message .= ". Errors: " . implode('; ', array_slice($allErrors, 0, 10));
            if (count($allErrors) > 10) {
                $message .= "... and " . (count($allErrors) - 10) . " more errors";
            }
        }

        return redirect()->route('mumineen.index')->with('success', $message);
    }
}
