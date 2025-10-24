<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class LessonForm extends Component
{
    public function render()
    {
        return view('livewire.admin.lesson-form')->layout('layouts.admin');
    }
}
