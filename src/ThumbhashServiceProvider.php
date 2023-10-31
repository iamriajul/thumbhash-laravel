<?php

namespace Riajul\Thumbhash;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class ThumbhashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        if ($this->app instanceof Application) {
            $this->bootLumen();
        } else {
            $this->bootLaravel();
        }
    }

    /**
     * Bootstrap laravel application events.
     */
    protected function bootLaravel(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->configPath() => config_path('thumbhash.php'),
            ], 'config');
        }
    }

    /**
     * Bootstrap lumen application events.
     */
    protected function bootLumen(): void
    {
        $this->app->configure('thumbhash');
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), 'thumbhash');

        $this->app->singleton('thumbhash', function ($app) {
            $config = $app['config']->get('thumbhash');

            return new Thumbhash(
                $config['resized-image-max-size']
            );
        });
    }

    /**
     * Get config file path.
     */
    protected function configPath(): string
    {
        return __DIR__ . '/../config/thumbhash.php';
    }
}
