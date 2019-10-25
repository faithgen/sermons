<?php

namespace FaithGen\Sermons\Providers;

use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\Observers\SermonObserver;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SermonsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/faithgen-sermons.php', 'faithgen-sermons');

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            if (config('faithgen-sdk.source')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/' => database_path('migrations')
                ], 'faithgen-sermons-migrations');

                $this->publishes([
                    __DIR__ . '/../storage/sermons/' => storage_path('app/public/sermons')
                ], 'faithgen-sermons-storage');
            }

            $this->publishes([
                __DIR__ . '/../config/faithgen-sermons.php' => config_path('faithgen-sermons.php')
            ], 'faithgen-sermons-config');
        }

        Sermon::observe(SermonObserver::class);
    }

    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/sermons.php');
          /* dd(config('faithgen-sdk.source'));
            if(config('faithgen-sdk.source'))*/
                $this->loadRoutesFrom(__DIR__.'/../routes/source.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            'prefix' => config('faithgen-sermons.prefix'),
            'namespace' => "FaithGen\Sermons\Http\Controllers",
            'middleware' => config('faithgen-sermons.middlewares'),
        ];
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
