<?php

namespace Studio1902\PeakTools\Widgets;

use Statamic\Facades\AssetContainer;
use Statamic\Widgets\Widget;
use Studio1902\PeakTools\Services\Service;

class ImagesMissingAlt extends Widget
{
    protected Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * The HTML that should be shown in the widget.
     *
     * @return string|\Illuminate\View\View
     */
    public function html()
    {
        $container = $this->config('container', 'assets');

        $containers = collect(is_array($container) ? $container : [$container]);

        $assets = $containers->reduce(
            fn ($assets, $container) => $assets->merge($this->service->getImagesWithMissingAlt($container)),
            collect(),
        );

        $assets = $assets->sortByDesc('last_modified')->values();

        return view('statamic-peak-tools::widgets.images-missing-alt', [
            'assets' => $assets->slice(0, $this->config('limit', 5)),
            'amount' => $assets->count(),
            'containers' => $containers->map(fn (string $container) => AssetContainer::findByHandle($container)->title()),
        ]);
    }
}
