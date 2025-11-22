<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
        // Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        // Auth::extend('jwt', function (Application $app, string $name, array $config) {

        //     // Return an instance of Illuminate\Contracts\Auth\Guard...

 

        //     // return new JwtGuard(Auth::createUserProvider($config['provider']));

        // });
    }
}
