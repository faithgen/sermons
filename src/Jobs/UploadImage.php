<?php

namespace FaithGen\Sermons\Jobs;

use FaithGen\SDK\Traits\UploadsImages;
use FaithGen\Sermons\Models\Sermon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManager;

class UploadImage implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        UploadsImages;

    public bool $deleteWhenMissingModels = true;
    protected Sermon $sermon;

    protected string $image;

    /**
     * Create a new job instance.
     *
     * @param Sermon $sermon
     * @param string $image
     */
    public function __construct(Sermon $sermon, string $image)
    {
        $this->sermon = $sermon;
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @param ImageManager $imageManager
     *
     * @return void
     */
    public function handle(ImageManager $imageManager)
    {
        if ($this->sermon->image()->exists()) {
            $fileName = $this->sermon->image->name;
            $this->uploadImages($this->sermon, [$this->image], $imageManager, $fileName);
        } else {
            $this->uploadImages($this->sermon, [$this->image], $imageManager);
        }
    }
}
