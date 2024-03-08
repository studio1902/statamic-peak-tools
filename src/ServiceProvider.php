<?php

namespace Studio1902\PeakTools;

use Statamic\Providers\AddonServiceProvider;
use Studio1902\PeakTools\Widgets\ImagesMissingAlt;
use Studio1902\PeakTools\Listeners\UpdateImagesMissingAltCacheListener;

class ServiceProvider extends AddonServiceProvider
{
    protected $subscribe = [
        UpdateImagesMissingAltCacheListener::class,
    ];

    protected $widgets = [
        ImagesMissingAlt::class
    ];

    protected $updateScripts = [
        \Studio1902\PeakTools\Updates\UpdateFormJSDriver::class,
        \Studio1902\PeakTools\Updates\UpdateFormFields::class,
        \Studio1902\PeakTools\Updates\UpdateFormErrorHandling::class,
        \Studio1902\PeakTools\Updates\UpdateButtonAttributeTags::class,
        \Studio1902\PeakTools\Updates\UpdateImagesBlueprintWithExemptToggle::class,
        \Studio1902\PeakTools\Updates\UpdateHoneypotField::class,
    ];

    public function bootAddon()
    {
        $this->registerPublishableConfig();
        $this->registerPublishableViews();
    }

    protected function registerPublishableConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/statamic/peak_images_missing_alt.php' => config_path('statamic/peak_images_missing_alt.php'),
        ], 'statamic-peak-tools-config');
    }

    protected function registerPublishableViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/statamic-peak-tools'),
        ], 'statamic-peak-tools-views');
    }
}
