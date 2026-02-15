<?php

use Illuminate\Support\Facades\Route;
use Statamic\Facades\Site;
use Statamic\Facades\URL;
use Stringy\StaticStringy as Stringy;

Site::all()->each(function (\Statamic\Sites\Site $site) {
    $relativeSiteUrl = URL::makeRelative($site->url());

    // The Manifest route to the manifest.json
    $manifestUrl = Site::all()->count() === 1
        ? $relativeSiteUrl . '/site.webmanifest'
        : Stringy::ensureRight($relativeSiteUrl, '/' . $site->handle()) . '/site.webmanifest';

    Route::statamic(URL::tidy($manifestUrl), 'statamic-peak-browser-appearance::manifest/manifest', [
        'layout' => null,
        'content_type' => 'application/json'
    ])->name('manifest.' . $site->handle());
});
