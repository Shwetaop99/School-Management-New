<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\School;

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
    // Preserve existing super admin permission logic.
    Gate::before(function ($user, $ability) {
        if ($user->role?->name === 'super_admin') {
            return true;
        }

        return $user->hasPermission($ability) ? true : null;
    });

    // Share the logged-in school with the admin layout.
   View::composer('layouts.app', function ($view) {
    $school = null;

    $user = auth()->user();

    if ($user && $user->school_id) {
        $school = School::find($user->school_id);
    }

    $view->with('school', $school);
});
}
}
