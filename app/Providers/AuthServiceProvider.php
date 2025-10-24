<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use App\Policies\QuizPolicy;
use App\Policies\QuestionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        Lesson::class => LessonPolicy::class,
        Quiz::class => QuizPolicy::class,
        Question::class => QuestionPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
