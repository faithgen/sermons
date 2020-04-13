<?php

namespace FaithGen\Sermons\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \FaithGen\Sermons\Events\Created::class => [
            \FaithGen\Sermons\Listeners\Created\UploadImage::class,
            \FaithGen\Sermons\Listeners\Created\ProcessImage::class,
            \FaithGen\Sermons\Listeners\Created\MessageFollowUsers::class,
            \FaithGen\Sermons\Listeners\Created\S3Upload::class,
        ],
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
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
