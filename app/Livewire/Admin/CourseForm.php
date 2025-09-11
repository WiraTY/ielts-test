<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class CourseForm extends Component
{
    public function render()
    {
        return view('livewire.admin.course-form')->layout('layouts.admin');
    }
}
