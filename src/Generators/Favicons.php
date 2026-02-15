<?php

namespace Studio1902\PeakTools\Generators;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Globals\Variables;

class Favicons
{
    public static function generate(): void
    {
        if (!($globals = GlobalSet::findByHandle('browser_appearance'))) {
            return;
        }

        Site::all()?->each(function (\Statamic\Sites\Site $site) use ($globals) {
            /** @var Variables $set */
            $set = $globals->in($site->handle());

            // Skip sites which do not use favicons
            if (!$set->value('use_favicons')) {
                return;
            }

            $svg = $set->value('svg');
            $background = $set->value('background');

            self::createThumbnail($svg, $site->handle() . '/icon-180.png', 'png32', 180, 180, $background, 15);
            self::createThumbnail($svg, $site->handle() . '/icon-512.png', 'png32', 512, 512, $background, 15);
            self::createThumbnail($svg, $site->handle() . '/favicon-16x16.png', 'png32', 16, 16, 'transparent', false);
            self::createThumbnail($svg, $site->handle() . '/favicon-32x32.png', 'png32', 32, 32, 'transparent', false);
            self::createThumbnail($svg, $site->handle() . '/favicon.ico', 'ico', 32, 32, 'transparent', false);
        });

        Artisan::call('cache:clear');
    }

    protected static function createThumbnail($import, $export, $format, $width, $height, $background, $border): void
    {
        $svg = Storage::disk('favicons')->get($import);
        $svgObj = simplexml_load_string($svg);
        $viewBox = explode(' ', $svgObj['viewBox']);
        $viewBoxWidth = $viewBox[2];
        $viewBoxHeight = $viewBox[3];
        if ($viewBoxWidth >= $viewBoxHeight) {
            $ratio = $width / $viewBoxWidth;
        } else {
            $ratio = $height / $viewBoxHeight;
        }

        $img = new \Imagick();
        $img->setResolution($viewBoxWidth * $ratio * 2, $viewBoxHeight * $ratio * 2);
        $img->setBackgroundColor(new \ImagickPixel($background));
        $img->readImageBlob($svg);
        $img->resizeImage($viewBoxWidth * $ratio, $viewBoxHeight * $ratio, \Imagick::FILTER_LANCZOS, 1);

        if ($viewBoxWidth >= $viewBoxHeight) {
            $compensateHeight = $height - ($viewBoxHeight * $ratio);
            $img->extentImage($width, $height - $compensateHeight / 2, 0, $compensateHeight * -.5);
            $img->extentImage($width, $height, 0, 0);
        } else {
            $compensateWidth = $width - ($viewBoxWidth * $ratio);
            $img->extentImage($width - $compensateWidth / 2, $height, $compensateWidth * -.5, 0);
            $img->extentImage($width, $height, 0, 0);
        }

        if ($border) {
            $img->borderImage($background, $border, $border);
            $img->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        }

        $img->setImageFormat($format);
        Storage::disk('favicons')->put($export, $img->getImageBlob());
        $img->clear();
        $img->destroy();
    }

}
