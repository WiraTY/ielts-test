<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelProgressionReportController extends Controller
{
    /**
     * Display level progression tracking report
     */
    public function index(Request $request)
    {
        // Build query for users with level progression data
        $query = User::where('role', 'student')
            ->with(['progress.lesson.course'])
            ->select('users.*')
            ->leftJoin('progress', 'users.id', '=', 'progress.user_id')
            ->leftJoin('lessons', 'progress.lesson_id', '=', 'lessons.id')
            ->leftJoin('courses', 'lessons.course_id', '=', 'courses.id')
            ->groupBy('users.id');
            
        // Apply filters
        if ($request->filled('level')) {
            $query->where('users.current_level', $request->level);
        }
        
        if ($request->filled('search')) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }
        
        // Get paginated results
        $progressions = $query->paginate(20);
        
        // Enhance the collection with progression data
        $progressions->getCollection()->transform(function ($user) {
            // Get courses for user's current level
            $levelCourses = Course::byLevel($user->current_level)->get();
            
            // Calculate completion percentage
            $totalCourses = $levelCourses->count();
            $completedCourses = 0;
            
            foreach ($levelCourses as $course) {
                $isCompleted = true;
                foreach ($course->lessons as $lesson) {
                    $progress = $user->progress->firstWhere('lesson_id', $lesson->id);
                    if (!$progress || $progress->status !== 'completed') {
                        $isCompleted = false;
                        break;
                    }
                }
                
                if ($isCompleted) {
                    $completedCourses++;
                }
            }
            
            $completionPercentage = $totalCourses > 0 ? ($completedCourses / $totalCourses) * 100 : 0;
            
            // Get first and last progress dates
            $userProgress = $user->progress;
            $startedAt = $userProgress->min('created_at');
            $completedAt = $userProgress->where('status', 'completed')->max('completed_at');
            
            return (object) [
                'user' => $user,
                'total_courses' => $totalCourses,
                'completed_courses' => $completedCourses,
                'completion_percentage' => $completionPercentage,
                'status' => $completionPercentage == 100 ? 'completed' : 'in_progress',
                'started_at' => $startedAt ? new \DateTime($startedAt) : null,
                'completed_at' => $completedAt ? new \DateTime($completedAt) : null,
            ];
        });
        
        // Calculate statistics
        $totalUsers = User::where('role', 'student')->count();
        
        // Filter for statistics
        $statsQuery = User::where('role', 'student');
        if ($request->filled('level')) {
            $statsQuery->where('current_level', $request->level);
        }
        if ($request->filled('search')) {
            $statsQuery->where('name', 'like', '%' . $request->search . '%');
        }
        
        $filteredUsers = $statsQuery->get();
        $inProgressCount = 0;
        $completedCount = 0;
        $totalCompletion = 0;
        $totalTimeToComplete = 0;
        $completedUserCount = 0;
        
        foreach ($filteredUsers as $user) {
            // Get courses for user's current level
            $levelCourses = Course::byLevel($user->current_level)->get();
            
            // Calculate completion percentage
            $totalCourses = $levelCourses->count();
            $completedCourses = 0;
            
            foreach ($levelCourses as $course) {
                $isCompleted = true;
                foreach ($course->lessons as $lesson) {
                    $progress = $user->progress->firstWhere('lesson_id', $lesson->id);
                    if (!$progress || $progress->status !== 'completed') {
                        $isCompleted = false;
                        break;
                    }
                }
                
                if ($isCompleted) {
                    $completedCourses++;
                }
            }
            
            $completionPercentage = $totalCourses > 0 ? ($completedCourses / $totalCourses) * 100 : 0;
            
            if ($completionPercentage == 100) {
                $completedCount++;
            } else {
                $inProgressCount++;
            }
            
            $totalCompletion += $completionPercentage;
            
            // Calculate time to complete if user has completed courses
            if ($completedCourses > 0) {
                $userProgress = $user->progress;
                $startedAt = $userProgress->min('created_at');
                $completedAt = $userProgress->where('status', 'completed')->max('completed_at');
                
                if ($startedAt && $completedAt) {
                    $interval = (new \DateTime($startedAt))->diff(new \DateTime($completedAt));
                    $totalTimeToComplete += $interval->days;
                    $completedUserCount++;
                }
            }
        }
        
        $averageCompletion = $filteredUsers->count() > 0 ? $totalCompletion / $filteredUsers->count() : 0;
        $averageTimeToComplete = $completedUserCount > 0 ? round($totalTimeToComplete / $completedUserCount) . ' days' : 'N/A';
        
        return view('admin.reports.level-progression', compact(
            'progressions',
            'totalUsers',
            'inProgressCount',
            'completedCount',
            'averageCompletion',
            'averageTimeToComplete'
        ));
    }
}