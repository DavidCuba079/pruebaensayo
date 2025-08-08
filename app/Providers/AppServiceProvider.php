<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        try {
            // Ensure the filesystem is registered first
            if (!$this->app->bound('files')) {
                $this->app->instance('files', new Filesystem());
            }

            // Set cache configuration
            Config::set('cache.default', 'array');
            
            // Register cache manager
            $this->app->singleton('cache', function ($app) {
                return new CacheManager($app);
            });

            // Register cache store
            $this->app->singleton('cache.store', function ($app) {
                return $app['cache']->driver();
            });

            // Bind the cache repository
            $this->app->singleton('cache.psr6', function ($app) {
                return new \Illuminate\Cache\Repository($app['cache.store']);
            });

            // Initialize cache
            $this->app->make('cache');
            $this->app->make('cache.store');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            if ($this->app->bound('log')) {
                Log::error('Cache initialization error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
