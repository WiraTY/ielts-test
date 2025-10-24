<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Progress;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        // Get statistics
        $activeUsers = User::count();
        $quizzesCompleted = QuizAttempt::where('status', 'completed')->count();
        $avgQuizScore = QuizAttempt::where('status', 'completed')->avg('score') ?? 0;
        $lessonsCompleted = Progress::where('status', 'completed')->count();
        
        // Get user progress data
        $userProgress = Progress::with(['user', 'lesson.course'])
            ->where('status', 'completed')
            ->get()
            ->groupBy('user_id')
            ->map(function ($progresses) {
                $user = $progresses->first()->user;
                $course = $progresses->first()->lesson->course;
                $totalLessons = $course->lessons->count();
                $completedLessons = $progresses->count();
                
                return [
                    'user' => $user,
                    'course' => $course,
                    'completed_lessons' => $completedLessons,
                    'total_lessons' => $totalLessons,
                ];
            });
        
        // Get quiz results
        $quizResults = QuizAttempt::with(['user', 'quiz.lesson'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reports.index', compact(
            'activeUsers',
            'quizzesCompleted',
            'avgQuizScore',
            'lessonsCompleted',
            'userProgress',
            'quizResults'
        ));
    }
}