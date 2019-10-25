<?php

namespace FaithGen\Sermons\Listeners\Created;

use FaithGen\Sermons\Events\Created;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class S3Upload
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Created  $event
     * @return void
     */
    public function handle(Created $event)
    {
        if ($event->getSermon()->image()->exists()) {
            $ogImage = storage_path('app/public/sermons/original/') . $event->getSermon()->image->name;
            $thumb50 = storage_path('app/public/sermons/50-50/') . $event->getSermon()->image->name;
            $thumb100 = storage_path('app/public/sermons/100-100/') . $event->getSermon()->image->name;
            //todo upload to Amazon S3
        }
    }
}
