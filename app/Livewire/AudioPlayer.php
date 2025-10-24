<?php

namespace App\Livewire;

use Livewire\Component;

class AudioPlayer extends Component
{
    public $audioPath;
    public $lessonId;

    public function mount($lessonId, $audioPath)
    {
        $this->lessonId = $lessonId;
        $this->audioPath = $audioPath;
    }

    public function render()
    {
        return view('livewire.audio-player');
    }
}
