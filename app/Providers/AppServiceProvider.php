<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\HeadOfDepartment;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\Submission;
use App\Models\User;
use App\Observers\AdministratorObserver;
use App\Observers\AnnouncementObserver;
use App\Observers\CategoryObserver;
use App\Observers\HeadOfDepartmentObserver;
use App\Observers\LecturerObserver;
use App\Observers\StudentObserver;
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
        Lecturer::observe(LecturerObserver::class);
        Student::observe(StudentObserver::class);
        HeadOfDepartment::observe(HeadOfDepartmentObserver::class);
        User::observe(AdministratorObserver::class);
        Announcement::observe(AnnouncementObserver::class);

        Gate::define('super-admin', function (User $user) {
            return $user->role === 'super-admin'
                ? Response::allow()
                : Response::deny('You must be an super administrator.');
        });

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
