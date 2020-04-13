<?php

namespace FaithGen\Sermons\Providers;

use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\Policies\SermonPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Sermon::class => SermonPolicy::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
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
