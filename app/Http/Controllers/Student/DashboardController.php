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
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        Log::info('DashboardController@index called', ['user_id' => Auth::id()]);
        
        $user = Auth::user();
        
        // Get courses that user is enrolled in
        $enrolledCourses = Course::whereHas('lessons.progress', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['lessons' => function ($query) {
            $query->withCount('quizzes')
                  ->with('progress'); // Load progress relationship
        }])->get();
        
        Log::info('Enrolled courses count', ['count' => $enrolledCourses->count()]);

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
        
        Log::info('Courses with progress calculated', ['count' => count($coursesWithProgress)]);

        // Get user's quiz attempts with more details
        $quizAttempts = QuizAttempt::where('user_id', Auth::id())
            ->with(['quiz.lesson.course', 'quiz'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        Log::info('Quiz attempts retrieved', ['count' => $quizAttempts->count()]);

        // Calculate average score
        $totalScore = 0;
        $quizCount = $quizAttempts->count();
        foreach ($quizAttempts as $attempt) {
            $totalScore += $attempt->score;
        }
        $averageScore = $quizCount > 0 ? round($totalScore / $quizCount, 2) : 0;
        
        Log::info('Average score calculated', ['average_score' => $averageScore]);

        // Get lessons in progress (not completed)
        $lessonsInProgress = Lesson::whereHas('progress', function ($query) {
            $query->where('user_id', Auth::id())
                  ->where('status', 'in_progress');
        })->with(['course', 'progress'])->limit(3)->get();
        
        Log::info('Lessons in progress retrieved', ['count' => $lessonsInProgress->count()]);

        // Get recommended courses (trial courses not enrolled in) based on user's level
        $enrolledCourseIds = $enrolledCourses->pluck('id')->toArray();
        
        // Get courses accessible to the user based on their level
        $query = Course::where('is_trial', true)
            ->whereNotIn('id', $enrolledCourseIds)
            ->withCount('lessons');
            
        // Filter by user's unlocked levels
        if ($user->hasCompletedPlacementTest()) {
            $unlockedLevels = $user->unlocked_levels ?? [];
            $currentLevel = $user->current_level ?? 'starter';
            $allowedLevels = array_unique(array_merge($unlockedLevels, [$currentLevel]));
            $query->whereIn('level', $allowedLevels);
        } else {
            // If user hasn't taken placement test, only show starter level courses
            $query->where('level', 'starter');
        }
        
        $recommendedCourses = $query->limit(3)->get();
        
        Log::info('Recommended courses retrieved', ['count' => $recommendedCourses->count()]);

        // Get total lessons completed across all courses
        $totalLessonsCompleted = Progress::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->count();
            
        Log::info('Total lessons completed', ['count' => $totalLessonsCompleted]);

        // Get total quizzes passed
        $totalQuizzesPassed = $quizAttempts->filter(function ($attempt) {
            return $attempt->score >= $attempt->quiz->pass_score;
        })->count();
        
        Log::info('Total quizzes passed', ['count' => $totalQuizzesPassed]);

        // Get recent student recordings
        $recentRecordings = StudentRecording::where('user_id', Auth::id())
            ->with(['lesson.course'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        Log::info('Recent recordings retrieved', ['count' => $recentRecordings->count()]);
        
        Log::info('Returning dashboard view with data', [
            'courses_with_progress' => count($coursesWithProgress),
            'quiz_attempts' => $quizAttempts->count(),
            'lessons_in_progress' => $lessonsInProgress->count(),
            'recommended_courses' => $recommendedCourses->count(),
            'recent_recordings' => $recentRecordings->count()
        ]);

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
