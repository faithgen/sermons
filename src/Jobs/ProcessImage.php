<?php

namespace FaithGen\Sermons\Jobs;

use FaithGen\Sermons\Models\Sermon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManager;

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deleteWhenMissingModels = true;
    protected $sermon;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Sermon $sermon)
    {
        $this->sermon = $sermon;
    }

    /**
     * Execute the job.
     *
     * @param ImageManager $imageManager
     * @return void
     */
    public function handle(ImageManager $imageManager)
    {
        if ($this->sermon->image()->exists()) {
            $ogImage = storage_path('app/public/sermons/original/').$this->sermon->image->name;
            $thumb50 = storage_path('app/public/sermons/50-50/').$this->sermon->image->name;
            $thumb100 = storage_path('app/public/sermons/100-100/').$this->sermon->image->name;

            $imageManager->make($ogImage)->fit(100, 100, function ($constraint) {
                $constraint->upsize();
                $constraint->aspectRatio();
            }, 'center')->save($thumb100);

            $imageManager->make($ogImage)->fit(50, 50, function ($constraint) {
                $constraint->upsize();
                $constraint->aspectRatio();
            }, 'center')->save($thumb50);
        }
    }
}
