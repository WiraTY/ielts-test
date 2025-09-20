<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\StudentRecording;

class AudioRecorder extends Component
{
    use WithFileUploads;
    
    public $lessonId;
    public $duration; // in seconds
    public $isRecording = false;
    public $timeLeft;
    public $mediaRecorder;
    public $audioChunks = [];
    public $recordedAudio;
    public $existingRecording = null;
    public $newRecordingPath = null;
    public $hasNewRecording = false;
    public $showPreview = false;

    public function mount($lessonId, $duration)
    {
        $this->lessonId = $lessonId;
        $this->duration = $duration;
        $this->timeLeft = $duration;
        
        // Check if user already has a recording for this lesson
        $this->existingRecording = StudentRecording::where('user_id', auth()->id())
            ->where('lesson_id', $lessonId)
            ->first();
    }

    public function startRecording()
    {
        \Log::info('Start recording called', [
            'lesson_id' => $this->lessonId,
            'duration' => $this->duration,
            'user_id' => auth()->id(),
            'has_existing' => $this->existingRecording ? true : false
        ]);
        
        // Check if user already has a recording
        if ($this->existingRecording) {
            \Log::info('Existing recording found, showing SweetAlert2 confirmation', [
                'recording_id' => $this->existingRecording->id,
                'user_id' => auth()->id()
            ]);
            // Ask for confirmation before overwriting using SweetAlert2
            $this->dispatch('confirm-overwrite');
            return;
        }
        
        \Log::info('Starting new recording', [
            'lesson_id' => $this->lessonId,
            'user_id' => auth()->id()
        ]);
        
        $this->isRecording = true;
        $this->timeLeft = $this->duration;
        $this->audioChunks = [];
        $this->hasNewRecording = false;
        $this->newRecordingPath = null;
        $this->showPreview = false;
        
        // Countdown timer
        $this->dispatch('start-timer');
        
        // Trigger JavaScript event
        $this->dispatch('startRecording');
    }
    
    public function confirmOverwrite()
    {
        \Log::info('User confirmed overwrite via SweetAlert2', [
            'lesson_id' => $this->lessonId,
            'user_id' => auth()->id()
        ]);
        
        // Proceed with recording
        $this->isRecording = true;
        $this->timeLeft = $this->duration;
        $this->audioChunks = [];
        $this->hasNewRecording = false;
        $this->newRecordingPath = null;
        $this->showPreview = false;
        
        // Reset existing recording reference since we're about to overwrite it
        $this->existingRecording = null;
        
        \Log::info('Starting overwrite recording', [
            'lesson_id' => $this->lessonId,
            'user_id' => auth()->id()
        ]);
        
        // Countdown timer
        $this->dispatch('start-timer');
        
        // Trigger JavaScript event
        $this->dispatch('startRecording');
    }

    public function stopRecording()
    {
        \Log::info('Stop recording called', [
            'lesson_id' => $this->lessonId,
            'time_left' => $this->timeLeft,
            'is_recording' => $this->isRecording,
            'user_id' => auth()->id()
        ]);
        
        $this->isRecording = false;
        $this->dispatch('stop-timer');
        
        $this->timeLeft = $this->duration;
        
        // Trigger JavaScript event
        $this->dispatch('stopRecording');
    }

    public function updateRecordingDisplay($path)
    {
        \Log::info('Updating recording display', [
            'path' => $path,
            'user_id' => auth()->id()
        ]);
        
        // Update the component state to show the new recording
        $this->newRecordingPath = $path;
        $this->hasNewRecording = true;
        $this->showPreview = false; // Sembunyikan preview sementara
        
        // Refresh the existing recording data
        $this->existingRecording = StudentRecording::where('user_id', auth()->id())
            ->where('lesson_id', $this->lessonId)
            ->first();
            
        // Reset recording state
        $this->isRecording = false;
        $this->timeLeft = $this->duration;
        $this->audioChunks = [];
    }

    public function updateTimer()
    {
        \Log::info('Update timer called', [
            'is_recording' => $this->isRecording,
            'time_left' => $this->timeLeft,
            'duration' => $this->duration
        ]);
        
        // This method is called from the frontend every second
        if ($this->isRecording && $this->timeLeft > 0) {
            $this->timeLeft--;
        } elseif ($this->timeLeft <= 0 && $this->isRecording) {
            // Timer has reached zero, stop recording automatically
            \Log::info('Timer reached zero, stopping recording automatically');
            $this->stopRecording();
        }
    }
    
    public function deleteRecording()
    {
        if ($this->existingRecording) {
            \Log::info('Deleting recording', [
                'recording_id' => $this->existingRecording->id,
                'user_id' => auth()->id()
            ]);
            
            // Delete the file from storage
            if (Storage::disk('public')->exists($this->existingRecording->file_path)) {
                Storage::disk('public')->delete($this->existingRecording->file_path);
            }
            
            // Delete from database
            $this->existingRecording->delete();
            
            // Reset the existing recording
            $this->existingRecording = null;
            
            // Jika ini adalah recording yang baru saja disimpan, reset juga
            $this->hasNewRecording = false;
            $this->newRecordingPath = null;
            
            // Send success message
            $this->dispatch('recording-deleted');
        }
    }

    public function render()
    {
        return view('livewire.audio-recorder');
    }
}
