<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Quiz $quiz)
    {
        // Check if user is authorized to view this quiz's course
        Gate::authorize('view', $quiz->lesson->course);
        
        $questions = $quiz->questions;
        $lesson = $quiz->lesson;
        return view('admin.questions.index', compact('lesson', 'quiz', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Quiz $quiz)
    {
        // Check if user is authorized to update this quiz's course
        Gate::authorize('update', $quiz->lesson->course);
        
        $lesson = $quiz->lesson;
        return view('admin.questions.create', compact('lesson', 'quiz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Quiz $quiz)
    {
        // Check if user is authorized to update this quiz's course
        Gate::authorize('update', $quiz->lesson->course);
        
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,multi,essay',
            'score' => 'required|integer|min:1',
            'options' => 'array|required_if:type,mcq,multi',
            'options.*' => 'string|required_if:type,mcq,multi',
            'correct_answer' => 'required_if:type,mcq',
            'correct_answers' => 'array|required_if:type,multi',
        ]);

        $question = $quiz->questions()->create([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'score' => $request->score,
        ]);

        // Simpan options jika tipe bukan essay
        if ($request->type !== 'essay' && $request->has('options')) {
            $question->update(['options' => $request->options]);
        }

        // Simpan jawaban benar
        if ($request->type === 'mcq' && $request->has('correct_answer')) {
            $question->update(['answer_key' => ['correct' => (int)$request->correct_answer]]);
        } elseif ($request->type === 'multi' && $request->has('correct_answers')) {
            $question->update(['answer_key' => ['correct' => array_map('intval', $request->correct_answers)]]);
        }

        $lesson = $quiz->lesson;
        return redirect()->route('admin.courses.lessons.edit', [$lesson->course, $lesson])
                        ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, Question $question)
    {
        // Check if user is authorized to view this quiz's course
        Gate::authorize('view', $question->quiz->lesson->course);
        
        $lesson = $question->quiz->lesson;
        return view('admin.questions.show', compact('lesson', 'quiz', 'question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz, Question $question)
    {
        // Check if user is authorized to update this quiz's course
        Gate::authorize('update', $question->quiz->lesson->course);
        
        $lesson = $question->quiz->lesson;
        return view('admin.questions.edit', compact('lesson', 'quiz', 'question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        // Check if user is authorized to update this quiz's course
        Gate::authorize('update', $question->quiz->lesson->course);
        
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,multi,essay',
            'score' => 'required|integer|min:1',
            'options' => 'array|required_if:type,mcq,multi',
            'options.*' => 'string|required_if:type,mcq,multi',
            'correct_answer' => 'required_if:type,mcq',
            'correct_answers' => 'array|required_if:type,multi',
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'score' => $request->score,
        ]);

        // Simpan options jika tipe bukan essay
        if ($request->type !== 'essay' && $request->has('options')) {
            $question->update(['options' => $request->options]);
        }

        // Simpan jawaban benar
        if ($request->type === 'mcq' && $request->has('correct_answer')) {
            $question->update(['answer_key' => ['correct' => (int)$request->correct_answer]]);
        } elseif ($request->type === 'multi' && $request->has('correct_answers')) {
            $question->update(['answer_key' => ['correct' => array_map('intval', $request->correct_answers)]]);
        }

        $lesson = $question->quiz->lesson;
        return redirect()->route('admin.courses.lessons.edit', [$lesson->course, $lesson])
                        ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        // Check if user is authorized to update this quiz's course
        Gate::authorize('update', $question->quiz->lesson->course);
        
        $lesson = $question->quiz->lesson;
        $question->delete();
        return redirect()->route('admin.courses.lessons.edit', [$lesson->course, $lesson])
                        ->with('success', 'Question deleted successfully.');
    }

    /**
     * Upload image for editors (TinyMCE/CKEditor)
     */
    public function uploadImage(Request $request)
    {
        // This is an admin-only route, but we should still check authorization
        // Since this doesn't directly relate to a specific model, we'll check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        
        // Log the request for debugging
        \Log::info('Editor upload request received', [
            'url' => $request->url(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'input' => $request->all(),
        ]);

        try {
            // Validate the request
            $request->validate([
                'upload' => 'required|image|max:2048', // Max 2MB
            ]);

            // Store the image
            $path = $request->file('upload')->store('question-images', 'public');

            // Log successful upload
            \Log::info('Image uploaded successfully', ['path' => $path]);

            // Return the URL to CKEditor
            return response()->json([
                'url' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Editor upload error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
