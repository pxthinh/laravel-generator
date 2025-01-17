<?php

namespace Lbil\LaravelGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Lbil\LaravelGenerator\Helpers\ConfigHelper;

class LaravelGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $viewPath = __DIR__.'/../../resources/views';
        $this->loadViewsFrom($viewPath, 'laravel-generator');

        // Publish a config file
        $configPath = __DIR__.'/../../config/laravel-generator.php';
        $this->publishes([
            $configPath => config_path('laravel-generator.php'),
        ], 'config');

        // Publish views
        $this->publishes([
            __DIR__.'/../../resources/views' => config('laravel-generator.defaults.paths.views'),
        ], 'views');

        // Include routes
        $routePath = __DIR__.'/../../routes/web.php';
        if (file_exists($routePath)) {
            $this->loadRoutesFrom($routePath);
        }

        // Load package helpers file
        $helpersPath = __DIR__.'/../../common/helpers.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }

        // Load language files
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'laravel-generator');

        // Publish language files
        $this->publishes([
            __DIR__.'/../../lang' => resource_path('lang/vendor/laravel-generator'),
        ], 'lang');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $configPath = __DIR__.'/../../config/laravel-generator.php';
        $this->mergeConfigFrom($configPath, 'laravel-generator');

        $this->app->singleton('laravel-generator', function () {
            return new ConfigHelper();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array|null
     */
    public function provides(): ?array
    {
        return ['laravel-generator'];
    }
}
