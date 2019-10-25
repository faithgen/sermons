<?php

namespace FaithGen\Sermons\Providers;

use App\Observers\Ministry\SermonObserver;
use FaithGen\Sermons\Models\Sermon;
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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'faithgen-sermons-migrations');

            $this->publishes([
                __DIR__.'/../storage/sermons/' => storage_path('app/public/sermons')
            ], 'faithgen-sermons-storage');
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
