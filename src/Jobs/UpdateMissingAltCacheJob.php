<?php

namespace Studio1902\PeakTools\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Statamic\Assets\Asset;

class UpdateMissingAltCacheJob implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 2;

    protected $event;

    /**
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    public function handle(\Studio1902\PeakTools\Services\Service $service)
    {
        $service->clearCache($this->getAssetContainerHandle());
        $service->preloadCache($this->getAssetContainerHandle());
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware()
    {
        return [(new WithoutOverlapping($this->getAssetContainerHandle()))->releaseAfter(60)->expireAfter(180)];
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->getAssetContainerHandle();
    }

    private function getAssetContainerHandle(): string
    {
        /** @var Asset $asset */
        $asset = $this->event->asset;

        return $asset->container()->handle();
    }
}
