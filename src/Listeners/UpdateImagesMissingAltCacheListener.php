<?php

namespace Studio1902\PeakTools\Listeners;

use Statamic\Events\AssetDeleted;
use Statamic\Events\AssetSaved;
use Statamic\Events\AssetUploaded;
use Studio1902\PeakTools\Jobs\UpdateMissingAltCacheJob;

class UpdateImagesMissingAltCacheListener
{
    public function handle($event)
    {
        UpdateMissingAltCacheJob::dispatch($event);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe($events)
    {
        if (collect(config('statamic.cp.widgets'))->contains('type', 'images_missing_alt')) {
            $events->listen(AssetDeleted::class, [self::class, 'handle']);
            $events->listen(AssetSaved::class, [self::class, 'handle']);
            $events->listen(AssetUploaded::class, [self::class, 'handle']);
        }
    }
}
