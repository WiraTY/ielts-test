<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AudioSpeakingController extends Controller
{
    /**
     * Show the form for editing audio and speaking settings.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this lesson
        Gate::authorize('update', $lesson);
        
        return view('admin.lessons.audio-speaking.edit', compact('course', 'lesson'));
    }
    
    /**
     * Update audio and speaking settings.
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        // Check if user is authorized to update this lesson
        Gate::authorize('update', $lesson);
        
        // Validate the request
        $validatedData = $request->validate([
            'has_audio' => 'boolean',
            'listening_description' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|max:10240', // 10MB max
            'remove_audio' => 'boolean',
            'has_speaking_practice' => 'boolean',
            'speaking_description' => 'nullable|string',
            'speaking_duration' => 'nullable|integer|min:1|max:3600', // 1 detik hingga 1 jam
        ]);
        
        // Handle audio file upload
        if ($request->hasFile('audio_file')) {
            // Delete old audio file if exists
            if ($lesson->audio_path) {
                Storage::disk('public')->delete($lesson->audio_path);
            }
            
            // Store new audio file
            $audioPath = $request->file('audio_file')->store('lesson-audio', 'public');
            $validatedData['audio_path'] = $audioPath;
        } elseif ($request->input('remove_audio')) {
            // Remove audio file if requested
            if ($lesson->audio_path) {
                Storage::disk('public')->delete($lesson->audio_path);
            }
            $validatedData['audio_path'] = null;
        }
        
        // Update lesson with validated data
        $lesson->update($validatedData);
        
        return redirect()->route('admin.courses.lessons.show', [$course, $lesson])
            ->with('success', 'Audio and speaking settings updated successfully.');
    }
}
