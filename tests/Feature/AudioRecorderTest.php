<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Livewire\AudioRecorder;
use Livewire\Livewire;
use App\Models\User;

class AudioRecorderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that the timer counts down correctly.
     */
    public function test_timer_counts_down_correctly()
    {
        $user = User::factory()->create();
        $lesson = \App\Models\Lesson::factory()->create();
        
        Livewire::actingAs($user)
            ->test(AudioRecorder::class, ['lessonId' => $lesson->id, 'duration' => 5])
            ->call('startRecording')
            ->assertSet('isRecording', true)
            ->assertSet('timeLeft', 5);
    }

    /**
     * Test that the timer stops recording when it reaches zero.
     */
    public function test_timer_stops_recording_when_reaches_zero()
    {
        $user = User::factory()->create();
        $lesson = \App\Models\Lesson::factory()->create();
        
        Livewire::actingAs($user)
            ->test(AudioRecorder::class, ['lessonId' => $lesson->id, 'duration' => 2])
            ->call('startRecording')
            ->call('updateTimer') // 2 -> 1
            ->assertSet('timeLeft', 1)
            ->call('updateTimer') // 1 -> 0
            ->assertSet('timeLeft', 0)
            ->call('updateTimer') // Should stop recording
            ->assertSet('isRecording', false);
    }
}
