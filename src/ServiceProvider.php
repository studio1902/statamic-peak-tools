<?php

namespace Studio1902\PeakTools;

use Illuminate\Support\Facades\View;
use Statamic\Statamic;
use Statamic\Events\GlobalVariablesSaved;
use Statamic\Facades\GlobalSet;
use Statamic\Providers\AddonServiceProvider;
use Studio1902\PeakTools\Widgets\ImagesMissingAlt;
use Studio1902\PeakTools\Listeners\GenerateFavicons;
use Studio1902\PeakTools\Listeners\UpdateImagesMissingAltCacheListener;
use Studio1902\PeakTools\Tags\Picture;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Picture::class,
    ];

    protected $listen = [
        GlobalVariablesSaved::class => [
            GenerateFavicons::class,
        ],
    ];

    protected $routes = [
        'web' => __DIR__ . '/../routes/web.php',
    ];

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
        \Studio1902\PeakTools\Updates\AddTrackerEventsField::class,
        \Studio1902\PeakTools\Updates\RemoveLayoutLivePreviewPartial::class,
        \Studio1902\PeakTools\Updates\MergeBrowserAppearanceIntoTools::class,
        \Studio1902\PeakTools\Updates\MoveBrowserAppearancePublishedFiles::class,
    ];

    protected $vite = [
        'input' => [
            'resources/js/addon.js',
            'resources/css/addon.css',
        ],
        'publicDirectory' => 'resources/dist',
    ];

    public function bootAddon()
    {
        $this->registerPublishableFieldsets();
        $this->registerPublishableViews();
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'statamic-peak-tools');

        // Provide custom field conditions global tracker data to hide/show the event field in the button partial.
        View::composer('statamic::layout', function ($view) {
            if (auth()->guest()) {
                return;
            }

            Statamic::provideToScript([
                'use_fathom' => GlobalSet::findByHandle('seo')?->inDefaultSite()->get('use_fathom'),
                'use_google' => GlobalSet::findByHandle('seo')?->inDefaultSite()->get('tracker_type') === 'gtm' || GlobalSet::findByHandle('seo')?->inDefaultSite()->get('tracker_type') === 'gtag' || GlobalSet::findByHandle('seo')?->inDefaultSite()->get('tracker_type') === 'sgtm',
            ]);
        });
    }

    protected function registerPublishableFieldsets()
    {
        $this->publishes([
            __DIR__ . '/../resources/fieldsets' => resource_path('fieldsets/vendor/statamic-peak-tools'),
        ], 'statamic-peak-tools-fieldsets');
    }

    protected function registerPublishableViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/statamic-peak-tools'),
        ], 'statamic-peak-tools-views');
    }
}
