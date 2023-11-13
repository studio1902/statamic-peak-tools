<?php

namespace Studio1902\PeakTools;

use Statamic\Providers\AddonServiceProvider;
use Studio1902\PeakTools\Widgets\ImagesMissingAlt;
use Studio1902\PeakTools\Widgets\Listeners\UpdateImagesMissingAltCacheListener;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'actions' => __DIR__ . '/../routes/actions.php',
    ];

    protected $subscribe = [
        UpdateImagesMissingAltCacheListener::class,
    ];

    protected $widgets = [
        ImagesMissingAlt::class
    ];

    public function bootAddon()
    {
        $this->registerPublishableViews();
    }

    protected function registerPublishableViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/statamic-peak-tools'),
        ], 'statamic-peak-tools-views');
    }
}
