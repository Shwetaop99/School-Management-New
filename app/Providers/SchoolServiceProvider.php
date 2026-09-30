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
        if (Schema::hasTable('school_settings')) {
            View::share('school', SchoolSetting::first());
        } else {
            View::share('school', null);
        }

        logger('SchoolServiceProvider is loaded');
    }
}