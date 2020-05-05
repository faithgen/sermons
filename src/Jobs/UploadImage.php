<?php

namespace FaithGen\Sermons\Jobs;

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
        SerializesModels;

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
        if ($this->image) {
            if ($this->sermon->image()->exists()) {
                $fileName = $this->sermon->image->name;
            } else {
                $fileName = str_shuffle($this->sermon->id.time().time()).'.png';
            }
            $ogSave = storage_path('app/public/sermons/original/').$fileName;
            $imageManager->make($this->image)->save($ogSave);
            $this->sermon->image()->updateOrcreate([
                'imageable_id' => $this->sermon->id,
            ], [
                'name' => $fileName,
            ]);
        }
    }
}
