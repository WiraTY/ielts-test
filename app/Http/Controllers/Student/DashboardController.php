<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\QuizAttempt;
use App\Models\StudentRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get courses that user is enrolled in
        $enrolledCourses = Course::whereHas('lessons.progress', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['lessons' => function ($query) {
            $query->withCount('quizzes')
                  ->with('progress'); // Load progress relationship
        }])->get();

        // Calculate progress for each course using the new method
        $coursesWithProgress = [];
        $totalCoursesCompleted = 0;
        
        foreach ($enrolledCourses as $course) {
            $progress = $course->getUserProgress(Auth::id());
            
            $coursesWithProgress[] = [
                'course' => $course,
                'progress_percentage' => $progress['percentage'],
                'completed_lessons' => $progress['completed'],
                'total_lessons' => $progress['total'],
                'is_completed' => $progress['is_completed']
            ];
            
            if ($progress['is_completed']) {
                $totalCoursesCompleted++;
            }
        }

        // Get user's quiz attempts with more details
        $quizAttempts = QuizAttempt::where('user_id', Auth::id())
            ->with(['quiz.lesson.course', 'quiz'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate average score
        $totalScore = 0;
        $quizCount = $quizAttempts->count();
        foreach ($quizAttempts as $attempt) {
            $totalScore += $attempt->score;
        }
        $averageScore = $quizCount > 0 ? round($totalScore / $quizCount, 2) : 0;

        // Get lessons in progress (not completed)
        $lessonsInProgress = Lesson::whereHas('progress', function ($query) {
            $query->where('user_id', Auth::id())
                  ->where('status', 'in_progress');
        })->with(['course', 'progress'])->limit(3)->get();

        // Get recommended courses (trial courses not enrolled in)
        $enrolledCourseIds = $enrolledCourses->pluck('id')->toArray();
        $recommendedCourses = Course::where('is_trial', true)
            ->whereNotIn('id', $enrolledCourseIds)
            ->withCount('lessons')
            ->limit(3)
            ->get();

        // Get total lessons completed across all courses
        $totalLessonsCompleted = Progress::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->count();

        // Get total quizzes passed
        $totalQuizzesPassed = $quizAttempts->filter(function ($attempt) {
            return $attempt->score >= $attempt->quiz->pass_score;
        })->count();

        // Get recent student recordings
        $recentRecordings = StudentRecording::where('user_id', Auth::id())
            ->with(['lesson.course'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact(
            'coursesWithProgress', 
            'quizAttempts', 
            'averageScore',
            'lessonsInProgress',
            'recommendedCourses',
            'totalLessonsCompleted',
            'totalCoursesCompleted',
            'totalQuizzesPassed',
            'recentRecordings'
        ));
    }
}
