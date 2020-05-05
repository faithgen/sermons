<?php

namespace FaithGen\Sermons\Jobs;

use FaithGen\Sermons\Models\Sermon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessageFollowers implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

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
     * @return void
     */
    public function handle()
    {
        //
    }
}
