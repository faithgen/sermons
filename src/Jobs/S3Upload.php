<?php

namespace FaithGen\Sermons\Jobs;

use FaithGen\SDK\Traits\SavesToAmazonS3;
use FaithGen\Sermons\Models\Sermon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class S3Upload implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        SavesToAmazonS3;

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
        $this->saveFiles($this->sermon);
    }
}
