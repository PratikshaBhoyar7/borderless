<?php

namespace App\Providers;

use App\Auth\TokenGuard;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\ServiceProvider;

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
        // Register custom token guard for API authentication
        $this->app['auth']->extend('token', function ($app, $name, array $config) {
            return new TokenGuard(
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );
        });
    }
}
