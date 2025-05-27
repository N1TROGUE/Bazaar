<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Settings;
use Illuminate\Support\Facades\View;
use Sabberworm\CSS\Settings as CSSSettings;

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
        //Hiermee zijn de appSettings in elke view beschikbaar
            View::composer('*', function ($view) {
            $view->with('appSettings', Settings::first());
        });
    }
}
