<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is authorized to view courses
        Gate::authorize('viewAny', Course::class);
        
        $courses = Course::with('creator')->orderBy('order')->get();
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is authorized to create courses
        Gate::authorize('create', Course::class);
        
        return view('admin.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authorized to create courses
        Gate::authorize('create', Course::class);
        
        // Prepare data for validation
        $requestData = $request->all();
        // Ensure is_trial is always present as a boolean
        $requestData['is_trial'] = $request->has('is_trial') && $request->input('is_trial') != false;
        // Set default order value
        $requestData['order'] = $request->input('order', 0);
        
        // Validate the data
        $validatedData = validator($requestData, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_trial' => 'boolean', // Now this will always be true or false
            'order' => 'integer|min:0',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ])->validate();

        $slug = Str::slug($request->title);
        $count = Course::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        // Get the next order value if not provided
        $order = $validatedData['order'] ?? Course::max('order') + 1;

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course = Course::create([
            'title' => $validatedData['title'],
            'slug' => $slug,
            'description' => $validatedData['description'],
            'thumbnail_path' => $thumbnailPath,
            'is_trial' => $validatedData['is_trial'],
            'order' => $order,
            'created_by' => auth()->id(),
            'published_at' => $validatedData['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.courses.show', $course)->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // Check if user is authorized to view this course
        Gate::authorize('view', $course);
        
        $course->load(['lessons.creator', 'lessons.quizzes', 'creator']);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        // Check if user is authorized to update this course
        Gate::authorize('update', $course);
        
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        // Check if user is authorized to update this course
        Gate::authorize('update', $course);
        
        // Prepare data for validation
        $requestData = $request->all();
        // Ensure is_trial is always present as a boolean
        $requestData['is_trial'] = $request->has('is_trial') && $request->input('is_trial') != false;
        // Set order value
        $requestData['order'] = $request->input('order', $course->order);
        
        // Validate the data
        $validatedData = validator($requestData, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_trial' => 'boolean', // Now this will always be true or false
            'order' => 'integer|min:0',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ])->validate();

        $slug = Str::slug($request->title);
        if ($course->slug !== $slug) {
            $count = Course::where('slug', 'LIKE', "{$slug}%")->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            $course->slug = $slug;
        }

        // Handle thumbnail upload
        $thumbnailPath = $course->thumbnail_path; // Keep existing thumbnail if not updated
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($course->thumbnail_path) {
                Storage::disk('public')->delete($course->thumbnail_path);
            }
            // Store new thumbnail
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'thumbnail_path' => $thumbnailPath,
            'is_trial' => $validatedData['is_trial'],
            'order' => $validatedData['order'],
            'published_at' => $validatedData['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.courses.show', $course)->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        // Check if user is authorized to delete this course
        Gate::authorize('delete', $course);
        
        // Delete thumbnail if exists
        if ($course->thumbnail_path) {
            Storage::disk('public')->delete($course->thumbnail_path);
        }
        
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
