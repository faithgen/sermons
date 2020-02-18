<?php

namespace FaithGen\Sermons\Observers;

use FaithGen\SDK\Traits\FileTraits;
use FaithGen\Sermons\Jobs\S3Upload;
use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\Events\Created;
use FaithGen\Sermons\Jobs\UploadImage;
use FaithGen\Sermons\Jobs\ProcessImage;
use FaithGen\Sermons\Jobs\MessageFollowers;

class SermonObserver
{
    use FileTraits;

    /**
     * Handle the sermon "created" event.
     *
     * @param Sermon $sermon
     * @return void
     */
    public function created(Sermon $sermon)
    {
        MessageFollowers::withChain([
            new UploadImage($sermon, request('image')),
            new ProcessImage($sermon),
            new S3Upload($sermon)
        ])->dispatch($sermon);
    }

    /**
     * Handle the sermon "updated" event.
     *
     * @param Sermon $sermon
     * @return void
     */
    public function updated(Sermon $sermon)
    {
        //
    }

    /**
     * Handle the sermon "deleted" event.
     *
     * @param Sermon $sermon
     * @return void
     */
    public function deleted(Sermon $sermon)
    {
        if ($sermon->image()->exists()) {
            $this->deleteFiles($sermon);
            $sermon->image()->delete();
        }
    }

    /**
     * Handle the sermon "restored" event.
     *
     * @param Sermon $sermon
     * @return void
     */
    public function restored(Sermon $sermon)
    {
        //
    }

    /**
     * Handle the sermon "force deleted" event.
     *
     * @param Sermon $sermon
     * @return void
     */
    public function forceDeleted(Sermon $sermon)
    {
        //
    }
}
