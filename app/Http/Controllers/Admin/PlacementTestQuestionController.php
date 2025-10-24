<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementTest;
use App\Models\PlacementTestQuestion;
use Illuminate\Http\Request;

class PlacementTestQuestionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(PlacementTest $placementTest)
    {
        return view('admin.placement-tests.questions.create', compact('placementTest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PlacementTest $placementTest)
    {
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'score' => 'required|integer|min:1',
            'order' => 'nullable|integer|min:0',
        ]);

        $options = [
            'A' => $request->option_a,
            'B' => $request->option_b,
            'C' => $request->option_c,
            'D' => $request->option_d,
        ];

        $placementTest->questions()->create([
            'question_text' => $request->question_text,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'score' => $request->score,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('admin.placement-tests.edit', $placementTest)
            ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PlacementTest $placementTest, PlacementTestQuestion $question)
    {
        return view('admin.placement-tests.questions.show', compact('placementTest', 'question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlacementTest $placementTest, PlacementTestQuestion $question)
    {
        return view('admin.placement-tests.questions.edit', compact('placementTest', 'question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlacementTest $placementTest, PlacementTestQuestion $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'score' => 'required|integer|min:1',
            'order' => 'nullable|integer|min:0',
        ]);

        $options = [
            'A' => $request->option_a,
            'B' => $request->option_b,
            'C' => $request->option_c,
            'D' => $request->option_d,
        ];

        $question->update([
            'question_text' => $request->question_text,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'score' => $request->score,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('admin.placement-tests.edit', $placementTest)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlacementTest $placementTest, PlacementTestQuestion $question)
    {
        $question->delete();

        return redirect()->route('admin.placement-tests.edit', $placementTest)
            ->with('success', 'Question deleted successfully.');
    }
}
