<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ReportList extends Component
{
    public function render()
    {
        return view('livewire.admin.report-list')->layout('layouts.app');
    }
}
