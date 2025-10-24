<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentRecording;
use Illuminate\Support\Facades\Auth;

class RecordingController extends Controller
{
    public function index()
    {
        $recordings = StudentRecording::where('user_id', Auth::id())
            ->with(['lesson.course'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.recordings.index', compact('recordings'));
    }
}
