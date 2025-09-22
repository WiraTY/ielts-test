<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementTest;
use App\Models\PlacementTestAttempt;
use App\Imports\PlacementTestQuestionsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PlacementTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $placementTests = PlacementTest::withCount('questions')->latest()->get();
        return view('admin.placement-tests.index', compact('placementTests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.placement-tests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1|max:180',
            'is_active' => 'boolean',
            'level_mapping' => 'nullable|array',
        ]);

        // Process level mapping with GSE scale
        $levelMapping = [];
        if ($request->has('level_mapping')) {
            foreach ($request->level_mapping as $mapping) {
                if (!empty($mapping['range']) && !empty($mapping['level'])) {
                    // Validate range format (e.g., "22-35")
                    if (preg_match('/^\d+-\d+$/', $mapping['range'])) {
                        // Validate level is one of the valid GSE levels
                        $validLevels = ['starter', 'elementary', 'pre-intermediate', 'intermediate', 'upper-intermediate', 'advanced'];
                        if (in_array($mapping['level'], $validLevels)) {
                            $levelMapping[$mapping['range']] = $mapping['level'];
                        }
                    }
                }
            }
        }

        // If no level mapping provided, use default GSE scale
        if (empty($levelMapping)) {
            $levelMapping = [
                "22-35" => "starter",              // GSE 22-35: Starter (A1-A1+)
                "30-42" => "elementary",           // GSE 30-42: Elementary (A1+-A2)
                "36-46" => "pre-intermediate",     // GSE 36-46: Pre-Intermediate (A2-B1-)
                "46-58" => "intermediate",         // GSE 46-58: Intermediate (B1)
                "57-67" => "upper-intermediate",   // GSE 57-67: Upper Intermediate (B2)
                "66-78" => "advanced"              // GSE 66-78: Advanced (C1)
            ];
        }

        $placementTest = PlacementTest::create([
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => $request->has('is_active'),
            'level_mapping' => $levelMapping,
        ]);

        return redirect()->route('admin.placement-tests.edit', $placementTest)
            ->with('success', 'Placement test created successfully with GSE scale level mapping.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PlacementTest $placementTest)
    {
        $placementTest->load(['questions' => function ($query) {
            $query->orderBy('order');
        }]);
        
        return view('admin.placement-tests.show', compact('placementTest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlacementTest $placementTest)
    {
        $placementTest->load(['questions' => function ($query) {
            $query->orderBy('order');
        }]);
        
        return view('admin.placement-tests.edit', compact('placementTest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlacementTest $placementTest)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1|max:180',
            'is_active' => 'boolean',
            'level_mapping' => 'nullable|array',
        ]);

        // Process level mapping with GSE scale
        $levelMapping = [];
        if ($request->has('level_mapping')) {
            foreach ($request->level_mapping as $mapping) {
                if (!empty($mapping['range']) && !empty($mapping['level'])) {
                    // Validate range format (e.g., "22-35")
                    if (preg_match('/^\d+-\d+$/', $mapping['range'])) {
                        // Validate level is one of the valid GSE levels
                        $validLevels = ['starter', 'elementary', 'pre-intermediate', 'intermediate', 'upper-intermediate', 'advanced'];
                        if (in_array($mapping['level'], $validLevels)) {
                            $levelMapping[$mapping['range']] = $mapping['level'];
                        }
                    }
                }
            }
        }

        // If no level mapping provided, use default GSE scale
        if (empty($levelMapping)) {
            $levelMapping = [
                "22-35" => "starter",              // GSE 22-35: Starter (A1-A1+)
                "30-42" => "elementary",           // GSE 30-42: Elementary (A1+-A2)
                "36-46" => "pre-intermediate",     // GSE 36-46: Pre-Intermediate (A2-B1-)
                "46-58" => "intermediate",         // GSE 46-58: Intermediate (B1)
                "57-67" => "upper-intermediate",   // GSE 57-67: Upper Intermediate (B2)
                "66-78" => "advanced"              // GSE 66-78: Advanced (C1)
            ];
        }

        $placementTest->update([
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => $request->has('is_active'),
            'level_mapping' => $levelMapping,
        ]);

        return redirect()->back()
            ->with('success', 'Placement test updated successfully with GSE scale level mapping.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlacementTest $placementTest)
    {
        // Check if placement test has attempts
        if ($placementTest->attempts()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete placement test with existing attempts.');
        }

        $placementTest->delete();

        return redirect()->route('admin.placement-tests.index')
            ->with('success', 'Placement test deleted successfully.');
    }

    /**
     * Import questions from Excel file
     */
    public function importQuestions(Request $request, PlacementTest $placementTest)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            'replace_existing' => 'boolean',
        ]);

        try {
            // Count existing questions if we're not replacing them
            $existingCount = 0;
            if (!$request->has('replace_existing')) {
                $existingCount = $placementTest->questions()->count();
            }

            // If replace existing is checked, delete all existing questions
            if ($request->has('replace_existing')) {
                $placementTest->questions()->delete();
            }

            // Import the questions
            $import = new PlacementTestQuestionsImport($placementTest);
            Excel::import($import, $request->file('excel_file'));
            
            // Count imported questions
            $importedCount = $placementTest->questions()->count() - $existingCount;
            
            return redirect()->back()
                ->with('success', "Questions imported successfully. {$importedCount} questions added.");
        } catch (\Exception $e) {
            \Log::error('Placement test import error: ' . $e->getMessage(), [
                'placement_test_id' => $placementTest->id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to import questions: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download Excel template for questions import
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="placement_test_questions_template.xlsx"',
        ];

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'placement_test_template');
        
        // Create a new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers
        $sheet->setCellValue('A1', 'Question Text');
        $sheet->setCellValue('B1', 'Option A');
        $sheet->setCellValue('C1', 'Option B');
        $sheet->setCellValue('D1', 'Option C');
        $sheet->setCellValue('E1', 'Option D');
        $sheet->setCellValue('F1', 'Correct Answer');
        $sheet->setCellValue('G1', 'Score');
        $sheet->setCellValue('H1', 'Order');
        
        // Add example data
        $sheet->setCellValue('A2', 'What is the capital of France?');
        $sheet->setCellValue('B2', 'London');
        $sheet->setCellValue('C2', 'Berlin');
        $sheet->setCellValue('D2', 'Paris');
        $sheet->setCellValue('E2', 'Madrid');
        $sheet->setCellValue('F2', 'C');
        $sheet->setCellValue('G2', '1');
        $sheet->setCellValue('H2', '1');
        
        // Save the spreadsheet
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        // Return the file
        return response()->download($tempFile, 'placement_test_questions_template.xlsx', $headers)->deleteFileAfterSend(true);
    }
    
    /**
     * Display placement test reports
     */
    public function reports(Request $request)
    {
        // Get all placement tests for filter dropdown
        $placementTests = PlacementTest::all();
        
        // Build query for attempts
        $query = PlacementTestAttempt::with(['user', 'placementTest'])
            ->orderBy('created_at', 'desc');
            
        // Apply filters
        if ($request->filled('placement_test_id')) {
            $query->where('placement_test_id', $request->placement_test_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Get paginated attempts
        $attempts = $query->paginate(20);
        
        // Calculate statistics
        $statsQuery = PlacementTestAttempt::query();
        
        if ($request->filled('placement_test_id')) {
            $statsQuery->where('placement_test_id', $request->placement_test_id);
        }
        
        if ($request->filled('date_from')) {
            $statsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $statsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        
        $totalAttempts = $statsQuery->count();
        $averageScore = $totalAttempts > 0 ? $statsQuery->avg('score') : 0;
        $highestScore = $totalAttempts > 0 ? $statsQuery->max('score') : 0;
        $lowestScore = $totalAttempts > 0 ? $statsQuery->min('score') : 0;
        
        // Level distribution
        $starterCount = $statsQuery->where('assigned_level', 'starter')->count();
        $beginnerCount = $statsQuery->where('assigned_level', 'beginner')->count();
        $elementaryCount = $statsQuery->where('assigned_level', 'elementary')->count();
        $intermediateCount = $statsQuery->where('assigned_level', 'intermediate')->count();
        $advancedCount = $statsQuery->where('assigned_level', 'advanced')->count();
        
        // Score distribution
        $scoreRange0_20 = $statsQuery->whereBetween('score', [0, 20])->count();
        $scoreRange21_40 = $statsQuery->whereBetween('score', [21, 40])->count();
        $scoreRange41_60 = $statsQuery->whereBetween('score', [41, 60])->count();
        $scoreRange61_80 = $statsQuery->whereBetween('score', [61, 80])->count();
        $scoreRange81_100 = $statsQuery->whereBetween('score', [81, 100])->count();
        
        return view('admin.placement-tests.reports', compact(
            'placementTests',
            'attempts',
            'totalAttempts',
            'averageScore',
            'highestScore',
            'lowestScore',
            'starterCount',
            'beginnerCount',
            'elementaryCount',
            'intermediateCount',
            'advancedCount',
            'scoreRange0_20',
            'scoreRange21_40',
            'scoreRange41_60',
            'scoreRange61_80',
            'scoreRange81_100'
        ));
    }
}
