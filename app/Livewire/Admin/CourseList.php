<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class CourseList extends Component
{
    public function render()
    {
        return view('livewire.admin.course-list')->layout('layouts.app');
    }
}
