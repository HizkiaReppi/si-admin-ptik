<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Submission;
use App\Models\User;
use App\Observers\CategoryObserver;
use App\Observers\SubmissionObserver;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Category::observe(CategoryObserver::class);
        Submission::observe(SubmissionObserver::class);
        
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin'
                ? Response::allow()
                : Response::deny('You must be an administrator.');
        });

        Gate::define('HoD', function (User $user) {
            return $user->role === 'HoD'
                ? Response::allow()
                : Response::deny('You must be an head of departement.');
        });

        Gate::define('lecturer', function (User $user) {
            return $user->role === 'lecturer'
                ? Response::allow()
                : Response::deny('You must be an lecturer.');
        });

        Gate::define('student', function (User $user) {
            return $user->role === 'student'
                ? Response::allow()
                : Response::deny('You must be an student.');
        });
    }
}
