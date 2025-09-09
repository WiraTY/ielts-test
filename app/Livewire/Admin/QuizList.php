<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class QuizList extends Component
{
    public function render()
    {
        return view('livewire.admin.quiz-list')->layout('layouts.app');
    }
}
