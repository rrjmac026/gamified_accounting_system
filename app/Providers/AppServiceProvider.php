<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\TaskSubmission;
use App\Observers\TaskSubmissionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ResetsUserPasswords::class, ResetUserPassword::class);
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TaskSubmission::observe(TaskSubmissionObserver::class);
    }
}
