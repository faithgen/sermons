<?php

namespace FaithGen\Sermons\Providers;

use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\Observers\SermonObserver;
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
        $this->mergeConfigFrom(__DIR__.'/../config/faithgen-sermons.php', 'faithgen-sermons');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'faithgen-sermons-migrations');

            $this->publishes([
                __DIR__.'/../storage/sermons/' => storage_path('app/public/sermons')
            ], 'faithgen-sermons-storage');

            $this->publishes([
                __DIR__.'/../config/faithgen-sermons.php' => config_path('faithgen-sermons.php')
            ], 'faithgen-sermons-config');
        }

        Sermon::observe(SermonObserver::class);
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
