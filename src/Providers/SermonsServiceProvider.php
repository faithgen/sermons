<?php

namespace FaithGen\Sermons\Providers;

use FaithGen\SDK\Traits\ConfigTrait;
use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\Observers\SermonObserver;
use Illuminate\Support\ServiceProvider;

class SermonsServiceProvider extends ServiceProvider
{
    use ConfigTrait;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/faithgen-sermons.php', 'faithgen-sermons');

        $this->registerRoutes(__DIR__ . '/../routes/sermons.php', __DIR__ . '/../routes/source.php');

        $this->setUpSourceFiles(function () {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations')
            ], 'faithgen-sermons-migrations');

            $this->publishes([
                __DIR__ . '/../storage/sermons/' => storage_path('app/public/sermons')
            ], 'faithgen-sermons-storage');
        });

        $this->publishes([
            __DIR__ . '/../config/faithgen-sermons.php' => config_path('faithgen-sermons.php')
        ], 'faithgen-sermons-config');

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

    /**
     * The config you want to be applied onto your routes
     * @return array the rules eg, middleware, prefix, namespace
     */
    function routeConfiguration(): array
    {
        return [
            'prefix' => config('faithgen-sermons.prefix'),
            'namespace' => "FaithGen\Sermons\Http\Controllers",
            'middleware' => config('faithgen-sermons.middlewares'),
        ];
    }
}
