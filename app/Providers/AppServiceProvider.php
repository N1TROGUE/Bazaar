<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\User;

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
        View::composer('*', function ($view) {
            $user = Auth::user();

            // Check of gebruiker ingelogd is en of het een admin of zakelijke adverteerder is
            if ($user && in_array($user->role_id, [3, 4])) {
                $view->with('appSettings', $user->settings);
            } else {
                // Optioneel: default instellingen tonen
                $view->with('appSettings', null);
            }
        });
    }
}
