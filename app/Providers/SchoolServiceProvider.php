<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SchoolServiceProvider extends ServiceProvider
{
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
        // Prevent errors when the school_settings table
        // has not been created yet.
        if (!Schema::hasTable('school_settings')) {
            return;
        }

        View::share('school', SchoolSetting::first());

        logger('SchoolServiceProvider is loaded');
    }
}
