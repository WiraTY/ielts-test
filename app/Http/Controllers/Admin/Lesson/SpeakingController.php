<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SpeakingController extends Controller
{
    /**
     * Show the form for editing the speaking practice.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this course (lessons belong to courses)
        Gate::authorize('update', $course);
        
        return view('admin.lessons.speaking.edit', compact('course', 'lesson'));
    }

    /**
     * Update the speaking practice in storage.
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this course (lessons belong to courses)
        Gate::authorize('update', $course);
        
        $request->validate([
            'speaking_duration' => 'nullable|integer|min:1|max:300', // 5 minutes max
            'speaking_description' => 'nullable|string',
            'has_speaking_practice' => 'nullable|boolean',
        ]);
        
        // Update lesson
        $lesson->update([
            'speaking_duration' => $request->speaking_duration,
            'speaking_description' => $request->speaking_description,
            'has_speaking_practice' => $request->has('has_speaking_practice') ? true : false,
        ]);
        
        return redirect()->route('admin.courses.lessons.edit', [$course, $lesson])
            ->with('success', 'Speaking practice updated successfully.');
    }
}