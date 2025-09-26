<?php

namespace App\Http\Controllers;

use App\Models\Sabeel;
use App\Models\Mumin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SabeelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view-sabeels')->only(['index', 'show']);
        $this->middleware('can:create-sabeels')->only(['create', 'store']);
        $this->middleware('can:edit-sabeels')->only(['edit', 'update']);
        $this->middleware('can:delete-sabeels')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sabeel::with(['mumineen']);

        // Filter out left sabeels by default unless explicitly requested
        if (!$request->filled('type') || $request->type !== 'left_sabeel') {
            $query->where('sabeel_type', '!=', 'left_sabeel');
        }

        // Apply filters
        if ($request->filled('sector')) {
            $query->where('sabeel_sector', $request->sector);
        }

        if ($request->filled('type')) {
            $query->where('sabeel_type', $request->type);
        }

        if ($request->filled('member_count_min')) {
            $query->has('mumineen', '>=', $request->member_count_min);
        }

        if ($request->filled('member_count_max')) {
            $query->has('mumineen', '<=', $request->member_count_max);
        }

        if ($request->filled('hof_status')) {
            if ($request->hof_status === 'has_hof') {
                $query->whereHas('mumineen', function ($q) {
                    $q->whereColumn('ITS_ID', 'sabeels.sabeel_hof');
                });
            } elseif ($request->hof_status === 'no_hof') {
                $query->whereDoesntHave('mumineen', function ($q) {
                    $q->whereColumn('ITS_ID', 'sabeels.sabeel_hof');
                });
            }
        }

        if ($request->filled('has_mobile')) {
            if ($request->has_mobile === 'yes') {
                $query->whereHas('mumineen', function ($q) {
                    $q->whereNotNull('mobile_number');
                });
            } else {
                $query->whereDoesntHave('mumineen', function ($q) {
                    $q->whereNotNull('mobile_number');
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sabeel_code', 'like', "%{$search}%")
                  ->orWhere('sabeel_address', 'like', "%{$search}%")
                  ->orWhere('sabeel_hof', 'like', "%{$search}%")
                  ->orWhereHas('mumineen', function ($subQ) use ($search) {
                      $subQ->where('full_name', 'like', "%{$search}%")
                           ->orWhere('ITS_ID', 'like', "%{$search}%");
                  });
            });
        }

        // Handle sorting
        $sortBy = $request->get('sort', 'sabeel_code');
        $sortDirection = $request->get('direction', 'asc');
        
        // For sabeel_code, sort numerically instead of alphabetically
        if ($sortBy === 'sabeel_code') {
            $query->orderByRaw('CAST(sabeel_code AS UNSIGNED) ' . $sortDirection);
        } elseif ($sortBy === 'member_count') {
            $query->withCount('mumineen')->orderBy('mumineen_count', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Apply pagination
        $sabeels = $query->paginate(15)->withQueryString();

        // Get statistics
        $statistics = [
            'total_sabeels' => Sabeel::count(),
            'active_sabeels' => Sabeel::where('sabeel_type', '!=', 'left_sabeel')->count(),
            'total_mumineen' => Mumin::count(),
            'sector_distribution' => Sabeel::selectRaw('sabeel_sector, count(*) as count')
                ->groupBy('sabeel_sector')
                ->pluck('count', 'sabeel_sector')
                ->toArray()
        ];

        return view('sabeels.index', compact('sabeels', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sabeels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sabeel_code' => 'required|string|max:255|unique:sabeels,sabeel_code',
            'sabeel_address' => 'required|string',
            'sabeel_sector' => 'required|in:ezzi,fakhri,hakimi,shujai,al_masjid_us_saifee,raj_township,zainy,student,mtnc,unknown',
            'sabeel_hof' => 'required|string|max:8',
            'sabeel_type' => 'required|in:regular,student,res_without_sabeel,moallemeen,regular_lock_joint,left_sabeel'
        ]);

        Sabeel::create([
            'sabeel_code' => $request->sabeel_code,
            'sabeel_address' => $request->sabeel_address,
            'sabeel_sector' => $request->sabeel_sector,
            'sabeel_hof' => $request->sabeel_hof,
            'sabeel_type' => $request->sabeel_type
        ]);

        return redirect()->route('sabeels.index')
            ->with('success', 'Sabeel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sabeel $sabeel)
    {
        $sabeel->load(['mumineen']);
        return view('sabeels.show', compact('sabeel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sabeel $sabeel)
    {
        return view('sabeels.edit', compact('sabeel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sabeel $sabeel)
    {
        $request->validate([
            'sabeel_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sabeels')->ignore($sabeel->id)
            ],
            'sabeel_address' => 'required|string',
            'sabeel_sector' => 'required|in:ezzi,fakhri,hakimi,shujai,al_masjid_us_saifee,raj_township,zainy,student,mtnc,unknown',
            'sabeel_hof' => 'required|string|max:8',
            'sabeel_type' => 'required|in:regular,student,res_without_sabeel,moallemeen,regular_lock_joint,left_sabeel'
        ]);

        $sabeel->update([
            'sabeel_code' => $request->sabeel_code,
            'sabeel_address' => $request->sabeel_address,
            'sabeel_sector' => $request->sabeel_sector,
            'sabeel_hof' => $request->sabeel_hof,
            'sabeel_type' => $request->sabeel_type
        ]);

        return redirect()->route('sabeels.index')
            ->with('success', 'Sabeel updated successfully.');
    }

    /**
     * Download sample file for import.
     */
    public function sample()
    {
        // Serve static CSV file
        $filePath = public_path('sabeels_sample.csv');
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Sample file not found.');
        }
        
        return response()->download($filePath, 'sabeels_sample.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Handle file import and show preview.
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv|max:10240'
        ]);

        try {
            $file = $request->file('import_file');
            
            // Handle CSV files differently
            if ($file->getClientOriginalExtension() === 'csv') {
                $data = [];
                $handle = fopen($file->getPathname(), 'r');
                $headers = fgetcsv($handle, 0, ',', '"');
                
                while (($row = fgetcsv($handle, 0, ',', '"')) !== false) {
                    if (count($row) === count($headers)) {
                        $data[] = array_combine($headers, $row);
                    } else {
                        // Skip malformed rows
                        continue;
                    }
                }
                fclose($handle);
            } else {
                // For now, only support CSV files
                return redirect()->back()->with('error', 'Excel files are not supported yet. Please use CSV format.');
            }

            // Validate required columns
            $requiredColumns = ['sabeel_code', 'sabeel_address', 'sabeel_sector', 'sabeel_hof', 'sabeel_type'];
            $headers = array_keys($data[0] ?? []);
            
            foreach ($requiredColumns as $column) {
                if (!in_array($column, $headers)) {
                    return redirect()->back()->with('error', "Missing required column: {$column}");
                }
            }

            // Process data and check for existing sabeels
            $processedData = [];
            $existingSabeels = [];
            
            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 because of header row and 0-based index
                
                // Validate row data
                $validation = validator($row, [
                    'sabeel_code' => 'required|string|max:255',
                    'sabeel_address' => 'required|string',
                    'sabeel_sector' => 'required|in:ezzi,fakhri,hakimi,shujai,al_masjid_us_saifee,raj_township,zainy,student,mtnc,unknown',
                    'sabeel_hof' => 'required|string|max:8',
                    'sabeel_type' => 'required|in:regular,student,res_without_sabeel,moallemeen,regular_lock_joint,left_sabeel'
                ]);
                
                if ($validation->fails()) {
                    return redirect()->back()->with('error', "Validation error in row {$rowNumber}: " . implode(', ', $validation->errors()->all()));
                }
                
                $processedData[] = [
                    'row_number' => $rowNumber,
                    'sabeel_code' => $row['sabeel_code'],
                    'sabeel_address' => $row['sabeel_address'],
                    'sabeel_sector' => $row['sabeel_sector'],
                    'sabeel_hof' => $row['sabeel_hof'],
                    'sabeel_type' => $row['sabeel_type']
                ];
                
                // Check if sabeel already exists
                $existingSabeel = Sabeel::where('sabeel_code', $row['sabeel_code'])->first();
                if ($existingSabeel) {
                    $existingSabeels[] = [
                        'row_number' => $rowNumber,
                        'sabeel_code' => $row['sabeel_code'],
                        'existing_data' => $existingSabeel,
                        'new_data' => $row
                    ];
                }
            }

            // Store data in session for processing
            session(['import_data' => $processedData, 'existing_sabeels' => $existingSabeels]);

            // Debug info
            $totalRecords = count($data);
            $processedRecords = count($processedData);
            $existingRecords = count($existingSabeels);
            $newRecords = $processedRecords - $existingRecords;

            return redirect()->route('sabeels.import.preview')->with('info', 
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
        $existingSabeels = session('existing_sabeels', []);
        
        if (empty($importData)) {
            return redirect()->route('sabeels.index')->with('error', 'No import data found.');
        }

        return view('sabeels.import-preview', compact('importData', 'existingSabeels'));
    }

    /**
     * Process the import with user selections.
     */
    public function processImport(Request $request)
    {
        $importData = session('import_data', []);
        $existingSabeels = session('existing_sabeels', []);
        
        if (empty($importData)) {
            return redirect()->route('sabeels.index')->with('error', 'No import data found.');
        }

        // Check if we need to process in chunks due to max_input_vars limit
        $totalRecords = count($importData);
        $maxInputVars = ini_get('max_input_vars');
        
        if ($totalRecords > ($maxInputVars * 0.8)) { // Use 80% of limit to be safe
            return $this->processImportInChunks($request, $importData, $existingSabeels);
        }

        $request->validate([
            'action.*' => 'required|in:skip,overwrite,create'
        ]);

        $imported = 0;
        $skipped = 0;
        $overwritten = 0;
        $errors = [];

        foreach ($importData as $data) {
            $sabeelCode = $data['sabeel_code'];
            $action = $request->input("action.{$sabeelCode}", 'skip');
            
            try {
                if ($action === 'skip') {
                    $skipped++;
                    continue;
                }
                
                if ($action === 'overwrite') {
                    // Update existing sabeel
                    $existingSabeel = Sabeel::where('sabeel_code', $sabeelCode)->first();
                    if ($existingSabeel) {
                        $existingSabeel->update([
                            'sabeel_address' => $data['sabeel_address'],
                            'sabeel_sector' => $data['sabeel_sector'],
                            'sabeel_hof' => $data['sabeel_hof'],
                            'sabeel_type' => $data['sabeel_type']
                        ]);
                        $overwritten++;
                    } else {
                        // Create new sabeel if it doesn't exist
                        Sabeel::create($data);
                        $imported++;
                    }
                } elseif ($action === 'create') {
                    // Create new sabeel
                    Sabeel::create($data);
                    $imported++;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing sabeel {$sabeelCode}: " . $e->getMessage();
            }
        }

        // Clear session data
        session()->forget(['import_data', 'existing_sabeels']);

        $message = "Import completed. Total records: {$totalRecords}, Imported: {$imported}, Overwritten: {$overwritten}, Skipped: {$skipped}";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode('; ', array_slice($errors, 0, 10)); // Limit to first 10 errors
            if (count($errors) > 10) {
                $message .= "... and " . (count($errors) - 10) . " more errors";
            }
        }

        return redirect()->route('sabeels.index')->with('success', $message);
    }

    /**
     * Process import in chunks to handle large datasets.
     */
    private function processImportInChunks(Request $request, $importData, $existingSabeels)
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
                $sabeelCode = $data['sabeel_code'];
                
                try {
                    // For large imports, assume all new records should be created
                    // and all existing records should be skipped (to avoid form input limits)
                    $existingSabeel = Sabeel::where('sabeel_code', $sabeelCode)->first();
                    
                    if ($existingSabeel) {
                        // Skip existing sabeels in large imports
                        $skipped++;
                    } else {
                        // Create new sabeels
                        Sabeel::create($data);
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error processing sabeel {$sabeelCode}: " . $e->getMessage();
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
        session()->forget(['import_data', 'existing_sabeels']);

        $totalRecords = count($importData);
        $message = "Large import completed. Total records: {$totalRecords}, Imported: {$totalImported}, Skipped: {$totalSkipped}, Overwritten: {$totalOverwritten}";
        if (!empty($allErrors)) {
            $message .= ". Errors: " . implode('; ', array_slice($allErrors, 0, 10));
            if (count($allErrors) > 10) {
                $message .= "... and " . (count($allErrors) - 10) . " more errors";
            }
        }

        return redirect()->route('sabeels.index')->with('success', $message);
    }

}
