<?php

namespace Studio1902\PeakTools\Updates;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class MoveBrowserAppearancePublishedFiles extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('9.0.1');
    }

    public function update()
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => resource_path(),
        ]);

        collect([
            "/views/vendor/statamic-peak-browser-appearance/manifest/manifest.antlers.html",
            "/views/vendor/statamic-peak-browser-appearance/snippets/_browser_appearance.antlers.html",
            "/fieldsets/vendor/statamic-peak-browser-appearance/globals_browser_appearance_favicons_overrides.yaml",
            "/fieldsets/vendor/statamic-peak-browser-appearance/globals_browser_appearance_favicons.yaml",
            "/fieldsets/vendor/statamic-peak-browser-appearance/globals_browser_appearance_general_theme.yaml",
            "/fieldsets/vendor/statamic-peak-browser-appearance/globals_browser_appearance_general_universal.yaml"
        ])->each(function ($file) use ($disk) {
            if (File::exists(resource_path($file))) {
                $disk->move($file, str_replace('statamic-peak-browser-appearance', 'statamic-peak-tools', $file));
                $this->console()->info("`$file` moved to new location.");
            }
        });
    }
}
