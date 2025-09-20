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
    return view('courses.show', compact('course'));
})->name('courses.show');

Route::get('/lessons/{course:slug}/{lesson:slug}', function (App\Models\Course $course, App\Models\Lesson $lesson) {
    return view('lessons.show', compact('course', 'lesson'));
})->middleware(['auth', 'verified'])
  ->name('lessons.show');

Route::get('/quizzes/{quiz}/start', function (App\Models\Quiz $quiz) {
    return view('quizzes.start', compact('quiz'));
})->middleware(['auth', 'verified'])
  ->name('quizzes.start');

Route::get('/quizzes/attempts/{attempt}', function (App\Models\QuizAttempt $attempt) {
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
    Route::resource('courses.lessons', App\Http\Controllers\Admin\LessonController::class);
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
});

require __DIR__.'/auth.php';
