<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCourseLevelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // If user is not authenticated, deny access
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access this content.');
        }
        
        // Get the course from the route parameters
        $course = $request->route('course');
        
        // If there's no course in the route, continue with the request
        if (!$course) {
            return $next($request);
        }
        
        // Check if user has access to this course level
        if (!$user->hasAccessToLevel($course->level)) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to this course level.');
        }
        
        return $next($request);
    }
}
