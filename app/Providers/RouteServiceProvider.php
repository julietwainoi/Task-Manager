<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // You can perform actions here when the provider boots
    }

    /**
     * Register the application's routes.
     *
     * @param Router $router
     * @return void
     */
    public function map(Router $router)
    {
        // Include your routes file
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php', $router);
        // If you have other route files, you can load them similarly
        // $this->loadRoutesFrom(__DIR__.'/../../routes/api.php', $router);
    }

    /**
     * Load routes from the given file.
     *
     * @param string $path
     * @param Router $router
     */
    protected function loadRoutesFrom($path, Router $router)
    {
        if (file_exists($path)) {
            require $path;
        }
    }
}
