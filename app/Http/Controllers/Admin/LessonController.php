<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        // Check if user is authorized to view courses (lessons belong to courses)
        Gate::authorize('view', $course);
        
        $lessons = $course->lessons()->orderBy('order')->get();
        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        // Check if user is authorized to create lessons (which requires course update access)
        Gate::authorize('update', $course);
        
        return view('admin.lessons.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        // Check if user is authorized to create lessons (which requires course update access)
        Gate::authorize('update', $course);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,published',
        ]);

        $slug = Str::slug($request->title);
        $count = Lesson::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $lesson = $course->lessons()->create([
            'slug' => $slug,
            'title' => $request->title,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'order' => $request->order ?? 0,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.courses.show', $course)->with('success', 'Lesson created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Lesson $lesson)
    {
        // Check if user is authorized to view this course (lessons belong to courses)
        Gate::authorize('view', $course);
        
        $lesson->load('quiz');
        return view('admin.lessons.show', compact('course', 'lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this course (lessons belong to courses)
        Gate::authorize('update', $course);
        
        $lesson->load('quiz.questions');
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this course (lessons belong to courses)
        Gate::authorize('update', $course);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,published',
            // Quiz validation rules
            'quiz_title' => 'nullable|string|max:255',
            'quiz_duration_minutes' => 'nullable|integer|min:1',
            'quiz_pass_score' => 'nullable|integer|min:0|max:100',
        ]);

        $slug = Str::slug($request->title);
        if ($lesson->slug !== $slug) {
            $count = Lesson::where('slug', 'LIKE', "{$slug}%")->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            $lesson->slug = $slug;
        }

        $lesson->update([
            'title' => $request->title,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'order' => $request->order ?? 0,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        // Handle quiz update or creation
        if ($request->filled('quiz_title')) {
            if ($lesson->quiz) {
                // Update existing quiz
                $lesson->quiz->update([
                    'title' => $request->quiz_title,
                    'duration_minutes' => $request->quiz_duration_minutes,
                    'pass_score' => $request->quiz_pass_score,
                ]);
            } else {
                // Create new quiz
                $lesson->quiz()->create([
                    'title' => $request->quiz_title,
                    'duration_minutes' => $request->quiz_duration_minutes,
                    'pass_score' => $request->quiz_pass_score,
                ]);
            }
        }

        return redirect()->route('admin.courses.lessons.edit', [$course, $lesson])->with('success', 'Lesson and quiz updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this course (lessons belong to courses)
        Gate::authorize('update', $course);
        
        $lesson->delete();
        return redirect()->route('admin.courses.show', $course)->with('success', 'Lesson deleted successfully.');
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
            $path = $request->file('upload')->store('lesson-images', 'public');

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
