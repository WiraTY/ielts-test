<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserLevelTrackingController extends Controller
{
    /**
     * Display user level tracking dashboard
     */
    public function index(Request $request)
    {
        // Build query for users
        $query = User::where('role', 'student');
        
        // Apply filters
        if ($request->filled('level')) {
            $query->where(function($q) use ($request) {
                $q->where('assigned_level', $request->level)
                  ->orWhere('current_level', $request->level);
            });
        }
        
        if ($request->filled('has_taken_test')) {
            $query->where('has_taken_placement_test', $request->has_taken_test == '1' ? true : false);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // Get paginated users
        $users = $query->paginate(20);
        
        // Calculate statistics
        $totalUsers = User::where('role', 'student')->count();
        $usersWithTest = User::where('role', 'student')->where('has_taken_placement_test', true)->count();
        
        $starterLevelCount = User::where('role', 'student')
            ->where(function($q) {
                $q->where('assigned_level', 'starter')
                  ->orWhere('current_level', 'starter');
            })
            ->count();
            
        $beginnerLevelCount = User::where('role', 'student')
            ->where(function($q) {
                $q->where('assigned_level', 'beginner')
                  ->orWhere('current_level', 'beginner');
            })
            ->count();
            
        $elementaryLevelCount = User::where('role', 'student')
            ->where(function($q) {
                $q->where('assigned_level', 'elementary')
                  ->orWhere('current_level', 'elementary');
            })
            ->count();
            
        $intermediateLevelCount = User::where('role', 'student')
            ->where(function($q) {
                $q->where('assigned_level', 'intermediate')
                  ->orWhere('current_level', 'intermediate');
            })
            ->count();
            
        $advancedLevelCount = User::where('role', 'student')
            ->where(function($q) {
                $q->where('assigned_level', 'advanced')
                  ->orWhere('current_level', 'advanced');
            })
            ->count();
        
        return view('admin.user-level-tracking', compact(
            'users',
            'totalUsers',
            'usersWithTest',
            'starterLevelCount',
            'beginnerLevelCount',
            'elementaryLevelCount',
            'intermediateLevelCount',
            'advancedLevelCount'
        ));
    }
}