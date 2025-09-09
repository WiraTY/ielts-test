<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Lesson $lesson)
    {
        // Check if user is authorized to view this lesson's course
        Gate::authorize('view', $lesson->course);
        
        $quizzes = $lesson->quizzes;
        return view('admin.quizzes.index', compact('lesson', 'quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Lesson $lesson)
    {
        // Check if user is authorized to update this lesson's course
        Gate::authorize('update', $lesson->course);
        
        return view('admin.quizzes.create', compact('lesson'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Lesson $lesson)
    {
        // Check if user is authorized to update this lesson's course
        Gate::authorize('update', $lesson->course);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100',
        ]);

        $quiz = $lesson->quizzes()->create([
            'title' => $request->title,
            'duration_minutes' => $request->duration_minutes,
            'pass_score' => $request->pass_score,
        ]);

        return redirect()->route('admin.courses.lessons.edit', [$lesson->course, $lesson])
                        ->with('success', 'Quiz created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson, Quiz $quiz)
    {
        // Check if user is authorized to view this lesson's course
        Gate::authorize('view', $lesson->course);
        
        $quiz->load('questions');
        return view('admin.quizzes.show', compact('lesson', 'quiz'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson, Quiz $quiz)
    {
        // Check if user is authorized to update this lesson's course
        Gate::authorize('update', $lesson->course);
        
        return view('admin.quizzes.edit', compact('lesson', 'quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson, Quiz $quiz)
    {
        // Check if user is authorized to update this lesson's course
        Gate::authorize('update', $lesson->course);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100',
        ]);

        $quiz->update([
            'title' => $request->title,
            'duration_minutes' => $request->duration_minutes,
            'pass_score' => $request->pass_score,
        ]);

        return redirect()->route('admin.courses.lessons.edit', [$lesson->course, $lesson])
                        ->with('success', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson, Quiz $quiz)
    {
        // Check if user is authorized to update this lesson's course
        Gate::authorize('update', $lesson->course);
        
        $quiz->delete();
        return redirect()->route('admin.courses.lessons.edit', [$lesson->course, $lesson])
                        ->with('success', 'Quiz deleted successfully.');
    }
}
