<?php

namespace FaithGen\Sermons\Listeners\Created;

use FaithGen\Sermons\Events\Created;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Intervention\Image\ImageManager;

class ProcessImage implements ShouldQueue
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * Create the event listener.
     *
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
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

            $this->imageManager->make($ogImage)->fit(100, 100, function ($constraint) {
                $constraint->upsize();
                $constraint->aspectRatio();
            }, 'center')->save($thumb100);

            $this->imageManager->make($ogImage)->fit(50, 50, function ($constraint) {
                $constraint->upsize();
                $constraint->aspectRatio();
            }, 'center')->save($thumb50);
        }
    }
}
