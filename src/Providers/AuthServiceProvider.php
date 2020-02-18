<?php

namespace FaithGen\Sermons\Providers;

use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\Policies\SermonPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Sermon::class => SermonPolicy::class
    ];
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //sermon gate
        Gate::define('sermon.update', [SermonPolicy::class, 'update']);
        Gate::define('sermon.delete', [SermonPolicy::class, 'delete']);
        Gate::define('sermon.view', [SermonPolicy::class, 'view']);
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
