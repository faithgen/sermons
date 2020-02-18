<?php

namespace FaithGen\Sermons\Jobs;

use Illuminate\Bus\Queueable;
use FaithGen\Sermons\Models\Sermon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class S3Upload implements ShouldQueue
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
     * @return void
     */
    public function handle()
    {
        //
    }
}
