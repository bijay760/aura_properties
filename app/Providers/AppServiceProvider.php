<?php

namespace App\Providers;

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
        $this->app->singleton(
            \App\Helpers\Auth::class,
            \App\Helpers\JwtHelper::class
        );
        $this->app->bind('App\Repositories\Contracts\RegisterInterface', 'App\Repositories\Register\RegisterRepository');
        $this->app->bind('App\Repositories\Contracts\ContentInterface', 'App\Repositories\Content\ContentRepository');
        $this->app->bind('App\Repositories\Contracts\AuthInterface', 'App\Repositories\Auth\AuthRepository');
        $this->app->bind('App\Repositories\Contracts\ProfileInterface', 'App\Repositories\Profile\ProfileRepository');
        $this->app->bind('App\Repositories\Contracts\PropertiesInterface', 'App\Repositories\Property\PropertyRepository');
    }
}
