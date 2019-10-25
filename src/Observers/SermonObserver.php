<?php

namespace App\Observers\Ministry;

use App\Events\Ministry\Sermon\Created;
use FaithGen\SDK\Traits\FileTraits;
use FaithGen\Sermons\Models\Sermon;

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
        event(new Created($sermon));
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
