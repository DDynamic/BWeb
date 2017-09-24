<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use App\Providers\UserServiceProvider;
use App\Providers\GuardServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('renweb', function ($app, array $config) {
            return new UserServiceProvider();
        });

        Auth::extend('renweb', function ($app, $name, array $config) {
            return new GuardServiceProvider('renweb', Auth::createUserProvider($config['provider']), $app->make('session.store'));
        });
    }
}
