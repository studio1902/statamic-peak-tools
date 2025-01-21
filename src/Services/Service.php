<?php

namespace Studio1902\PeakTools\Services;

use Illuminate\Support\Facades\Cache;
use Statamic\Facades\Asset;

class Service
{
    public function getCacheKey(string $container): string
    {
        return "widgets::ImagesMissingAlt.{$container}";
    }

    public function clearCache(string $container): void
    {
        Cache::forget($this->getCacheKey($container));
    }

    public function getImagesWithMissingAlt(string $container): array
    {
        return Cache::rememberForever(
            $this->getCacheKey($container),
            function () use ($container) {
                return Asset::query()
                    ->where('container', $container)
                    ->where('is_image', true)
                    ->where('exempt_from_alt', '!=', true)
                    ->whereNull('alt')
                    ->orderBy('last_modified', 'desc')
                    ->limit(100)
                    ->get()
                    ->toAugmentedArray();
            },
        );
    }

    public function preloadCache(string $container): void
    {
        $this->getImagesWithMissingAlt($container);
    }
}
