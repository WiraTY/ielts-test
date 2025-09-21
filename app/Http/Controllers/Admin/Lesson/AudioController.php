<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class AudioController extends Controller
{
    /**
     * Show the form for editing the audio listening practice.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this course (lessons belong to courses)
        Gate::authorize('update', $course);
        
        return view('admin.lessons.audio.edit', compact('course', 'lesson'));
    }

    /**
     * Update the audio listening practice in storage.
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this course (lessons belong to courses)
        Gate::authorize('update', $course);
        
        $request->validate([
            'audio_file' => 'nullable|file|mimes:mp3,wav,webm|max:5120', // 5MB max
            'listening_description' => 'nullable|string',
            'has_audio' => 'nullable|boolean',
        ]);
        
        // Handle audio file upload
        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            // Delete old audio file if exists
            if ($lesson->audio_path) {
                Storage::disk('public')->delete($lesson->audio_path);
            }
            
            $audioPath = $request->file('audio_file')->store('lesson-audio', 'public');
        } else {
            // Keep existing audio path if not uploading new file
            $audioPath = $lesson->audio_path;
        }
        
        // Update lesson
        $lesson->update([
            'audio_path' => $audioPath,
            'listening_description' => $request->listening_description,
            'has_audio' => $request->has('has_audio') ? true : false,
        ]);
        
        return redirect()->route('admin.courses.lessons.edit', [$course, $lesson])
            ->with('success', 'Audio listening practice updated successfully.');
    }
}