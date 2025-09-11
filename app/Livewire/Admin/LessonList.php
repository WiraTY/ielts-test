<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class LessonList extends Component
{
    public function render()
    {
        return view('livewire.admin.lesson-list')->layout('layouts.admin');
    }
}
