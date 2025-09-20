<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Lesson;
use App\Models\StudentRecording;
use Illuminate\Support\Facades\Gate;

class AudioController extends Controller
{
    /**
     * Store a newly recorded audio file.
     */
    public function store(Request $request)
    {
        Log::info('Audio recording request received', [
            'lesson_id' => $request->lesson_id,
            'user_id' => auth()->id(),
            'has_file' => $request->hasFile('audio'),
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type')
        ]);
        
        if (!$request->hasFile('audio')) {
            Log::error('No audio file in request');
            return response()->json([
                'success' => false,
                'message' => 'No audio file provided'
            ], 400);
        }

        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'audio' => 'required|file|mimes:wav,mp3,webm|max:5120', // 5MB max
        ]);
        
        try {
            // Check if user already has a recording for this lesson
            $existingRecording = StudentRecording::where('user_id', auth()->id())
                ->where('lesson_id', $request->lesson_id)
                ->first();
                
            // If existing recording found, delete the old file
            if ($existingRecording) {
                Log::info('Deleting existing recording', [
                    'recording_id' => $existingRecording->id,
                    'file_path' => $existingRecording->file_path
                ]);
                
                // Delete the old file from storage
                if (Storage::disk('public')->exists($existingRecording->file_path)) {
                    Storage::disk('public')->delete($existingRecording->file_path);
                }
                
                // Delete the database record
                $existingRecording->delete();
            }

            // Store the new audio file
            $path = $request->file('audio')->store('student-recordings', 'public');
            
            Log::info('Audio file stored', [
                'path' => $path,
                'user_id' => auth()->id(),
                'full_url' => asset('storage/' . $path)
            ]);
            
            // Save the recording to the database
            $recording = StudentRecording::create([
                'user_id' => auth()->id(),
                'lesson_id' => $request->lesson_id,
                'file_path' => $path,
                // Duration would need to be sent from the frontend or calculated
            ]);
            
            Log::info('Recording saved to database', [
                'recording_id' => $recording->id,
                'user_id' => auth()->id(),
                'file_path' => $recording->file_path
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Recording saved successfully',
                'path' => $path,
                'recording_id' => $recording->id,
                'full_url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save recording', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save recording: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a student recording.
     */
    public function destroy(StudentRecording $recording)
    {
        // Check if user owns this recording using policy
        Gate::authorize('delete', $recording);
        
        try {
            // Delete the file from storage
            if (Storage::disk('public')->exists($recording->file_path)) {
                Storage::disk('public')->delete($recording->file_path);
            }
            
            // Delete from database
            $recording->delete();
            
            Log::info('Recording deleted', [
                'recording_id' => $recording->id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Recording deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete recording', [
                'error' => $e->getMessage(),
                'recording_id' => $recording->id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete recording: ' . $e->getMessage()
            ], 500);
        }
    }
}
