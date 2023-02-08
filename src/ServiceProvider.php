<?php

namespace Studio1902\PeakTools;

use Statamic\Providers\AddonServiceProvider;
use Studio1902\PeakTools\Widgets\ImagesMissingAlt;

class ServiceProvider extends AddonServiceProvider
{
    protected $widgets = [
        ImagesMissingAlt::class
    ];

    public function bootAddon()
    {
        //
    }
}
