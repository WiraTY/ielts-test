<?php

use App\Livewire\CourseList;
use App\Livewire\CourseDetail;
use App\Livewire\LessonViewer;
use App\Livewire\QuizRunner;
use App\Livewire\QuizResult;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\RecordingController;
use App\Http\Controllers\Admin\AudioController;

Route::get('/', function () {
    return redirect()->route('courses.index');
});

Route::get('/courses', function () {
    $courses = \App\Models\Course::where('is_trial', true)
        ->whereNotNull('published_at')
        ->get();
    
    // Jika hanya ada satu course trial, arahkan langsung ke course tersebut
    if ($courses->count() == 1) {
        return redirect()->route('courses.show', $courses->first()->slug);
    }
    
    return view('courses.index');
})->name('courses.index');

Route::get('/courses/{course:slug}', function (App\Models\Course $course) {
    // Check if user is authenticated
    if (auth()->check()) {
        // For authenticated users, we still show the course but indicate if it's locked
        // The actual lesson access will be blocked in the lessons route
    } else {
        // For guest users, only allow access to starter level courses
        if ($course->level !== 'starter') {
            // Redirect to login with message
            return redirect()->route('login')->with('error', 'Please log in to access this course.');
        }
    }
    
    return view('courses.show', compact('course'));
})->name('courses.show');

Route::get('/lessons/{course:slug}/{lesson:slug}', function (App\Models\Course $course, App\Models\Lesson $lesson) {
    // Verify that the lesson belongs to the course
    if ($lesson->course_id !== $course->id) {
        abort(404);
    }
    
    // Check if user has access to this course level
    if (auth()->check() && !auth()->user()->hasAccessToLevel($course->level)) {
        return redirect()->route('courses.show', $course->slug)->with('error', 'You do not have access to this course level. Complete previous courses to unlock this content.');
    }
    
    return view('lessons.show', compact('course', 'lesson'));
})->middleware(['auth', 'verified'])
  ->name('lessons.show');

Route::get('/quizzes/{quiz}/start', function (App\Models\Quiz $quiz) {
    // Check if user has access to this quiz's course level
    if (!auth()->user()->hasAccessToLevel($quiz->lesson->course->level)) {
        return redirect()->route('dashboard')->with('error', 'You do not have access to this course level.');
    }
    
    return view('quizzes.start', compact('quiz'));
})->middleware(['auth', 'verified'])
  ->name('quizzes.start');

Route::get('/quizzes/attempts/{attempt}', function (App\Models\QuizAttempt $attempt) {
    // Check if the attempt belongs to the authenticated user
    if ($attempt->user_id !== auth()->id()) {
        abort(403);
    }
    
    // Check if user has access to this attempt's course level
    if (!auth()->user()->hasAccessToLevel($attempt->quiz->lesson->course->level)) {
        return redirect()->route('dashboard')->with('error', 'You do not have access to this course level.');
    }
    
    return view('quizzes.result', compact('attempt'));
})->middleware(['auth', 'verified'])
  ->name('quizzes.result');

// Student recordings
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/recordings', [RecordingController::class, 'index'])->name('recordings.index');
});

// View as Admin route
Route::get('/admin/view', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified', 'admin'])
  ->name('admin.view');

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
    Route::get('/courses/bulk-assign-level', [App\Http\Controllers\Admin\CourseController::class, 'showBulkAssignLevel'])->name('courses.bulk-assign-level');
    Route::post('/courses/bulk-assign-level', [App\Http\Controllers\Admin\CourseController::class, 'bulkAssignLevel'])->name('courses.bulk-assign-level');
    Route::resource('courses.lessons', App\Http\Controllers\Admin\LessonController::class);
    
    // Audio & Speaking routes for lessons
    Route::get('/courses/{course}/lessons/{lesson}/audio-speaking', [App\Http\Controllers\Admin\Lesson\AudioSpeakingController::class, 'edit'])->name('courses.lessons.audio-speaking.edit');
    Route::put('/courses/{course}/lessons/{lesson}/audio-speaking', [App\Http\Controllers\Admin\Lesson\AudioSpeakingController::class, 'update'])->name('courses.lessons.audio-speaking.update');
    
    Route::resource('lessons.quizzes', App\Http\Controllers\Admin\QuizController::class);
    Route::resource('quizzes.questions', App\Http\Controllers\Admin\QuestionController::class);
    
    // Image upload route for editors
    Route::post('/questions/upload-image', [App\Http\Controllers\Admin\QuestionController::class, 'uploadImage'])->name('questions.upload-image');
    Route::post('/lessons/upload-image', [App\Http\Controllers\Admin\LessonController::class, 'uploadImage'])->name('lessons.upload-image');
    
    // User management
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/enable', [App\Http\Controllers\Admin\UserController::class, 'enable'])->name('users.enable');
    Route::post('/users/{user}/disable', [App\Http\Controllers\Admin\UserController::class, 'disable'])->name('users.disable');
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/level-progression', [App\Http\Controllers\Admin\LevelProgressionReportController::class, 'index'])->name('reports.level-progression');
    Route::get('/user-level-tracking', [App\Http\Controllers\Admin\UserLevelTrackingController::class, 'index'])->name('user-level-tracking');
    
    // Placement Tests routes
    Route::resource('placement-tests', App\Http\Controllers\Admin\PlacementTestController::class);
    Route::post('/placement-tests/{placementTest}/import-questions', [App\Http\Controllers\Admin\PlacementTestController::class, 'importQuestions'])->name('placement-tests.import-questions');
    Route::get('/placement-tests/download-template', [App\Http\Controllers\Admin\PlacementTestController::class, 'downloadTemplate'])->name('placement-tests.download-template');
    Route::get('/placement-tests/reports', [App\Http\Controllers\Admin\PlacementTestController::class, 'reports'])->name('placement-tests.reports');
    Route::resource('placement-tests.questions', App\Http\Controllers\Admin\PlacementTestQuestionController::class);
});

// Audio recording storage - accessible by all authenticated users
Route::middleware(['auth', 'verified'])->post('/audio/store', [App\Http\Controllers\Admin\AudioController::class, 'store'])->name('audio.store');
Route::middleware(['auth', 'verified'])->delete('/audio/{recording}', [App\Http\Controllers\Admin\AudioController::class, 'destroy'])->name('audio.destroy');

// User routes

// User routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'updateProfileInformation'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Placement Tests routes for students
    Route::get('/placement-tests', function () {
        // Get the first active placement test or create a default one for testing
        $placementTest = \App\Models\PlacementTest::where('is_active', true)->first();
        if (!$placementTest) {
            // Redirect to dashboard with error message if no active placement test
            return redirect()->route('dashboard')->with('error', 'No active placement test available at the moment.');
        }
        return view('placement-tests.index', compact('placementTest'));
    })->middleware(['auth', 'verified'])->name('placement-tests.index');
    
    Route::get('/placement-tests/{placementTest}', function (\App\Models\PlacementTest $placementTest) {
        return view('placement-tests.show', compact('placementTest'));
    })->name('placement-tests.show');
    
    Route::get('/placement-tests/{placementTest}/start', function (\App\Models\PlacementTest $placementTest) {
        return view('placement-tests.start', compact('placementTest'));
    })->name('placement-tests.start');
    
    Route::get('/placement-tests/attempts/{attempt}', function (\App\Models\PlacementTestAttempt $attempt) {
        return view('placement-tests.result', compact('attempt'));
    })->name('placement-tests.result');
});

require __DIR__.'/auth.php';
