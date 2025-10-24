<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        View::replaceNamespace(
            'laravel-exceptions-renderer',
            resource_path('views/vendor/laravel-exceptions-renderer')
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners
        Event::listen(
            \App\Events\CourseCompleted::class,
            \App\Listeners\CheckLevelProgression::class
        );
    }
}
