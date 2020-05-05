<?php

namespace FaithGen\Sermons\Jobs;

use FaithGen\SDK\Traits\ProcessesImages;
use FaithGen\Sermons\Models\Sermon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManager;

class ProcessImage implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        ProcessesImages;

    public bool $deleteWhenMissingModels = true;
    protected Sermon $sermon;

    /**
     * Create a new job instance.
     *
     * @param Sermon $sermon
     */
    public function __construct(Sermon $sermon)
    {
        $this->sermon = $sermon;
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
        $this->processImage($imageManager, $this->sermon);
    }
}
