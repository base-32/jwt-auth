<?php

namespace CarterParker\JWTAuth\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class JWTAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addRefreshTokenMiddleware();
        $this->registerConfig();
        $this->registerApiRoutes();
    }

    /**
     * Register the authentication routes with a custom namespace. 
     */
    protected function registerApiRoutes()
    {
        Route::prefix(config('jwt-auth.prefix', '/'))
            ->namespace('CarterParker\\JWTAuth\\Http\\Controllers')
            ->group(__DIR__ . './../../routes/api.php');
    }

    /**
     * Add the refresh token middleware to use on routes.
     */
    protected function addRefreshTokenMiddleware()
    {
        $this->app['router']->aliasMiddleware('refresh', 'Tymon\JWTAuth\Http\Middleware\RefreshToken');
    }

    /**
     * Register and merge existing configurations.
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/./../../config/auth.php',
            'auth'
        );

        $this->publishes([
            __DIR__ . '/./../../config/jwt.php' => config_path('jwt.php'),
            __DIR__ . '/./../../config/jwt-auth.php' => config_path('jwt-auth.php'),
        ]);
    }
}
