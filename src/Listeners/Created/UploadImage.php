<?php

namespace FaithGen\Sermons\Listeners\Created;

use FaithGen\Sermons\Events\Created;
use Intervention\Image\ImageManager;

class UploadImage
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
     * @param Created $event
     * @return void
     */
    public function handle(Created $event)
    {
        if (request()->has('image')) {
            if ($event->getSermon()->image()->exists())
                $fileName = $event->getSermon()->image->name;
            else
                $fileName = str_shuffle($event->getSermon()->id . time() . time()) . '.png';
            $ogSave = storage_path('app/public/sermons/original/') . $fileName;
            $this->imageManager->make(request()->image)->save($ogSave);
            $event->getSermon()->image()->updateOrcreate([
                'imageable_id' => $event->getSermon()->id
            ], [
                'name' => $fileName
            ]);
        }
    }
}
