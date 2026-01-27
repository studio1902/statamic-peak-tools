<?php

namespace Studio1902\PeakTools\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Statamic\UpdateScripts\UpdateScript;

class RemoveLayoutLivePreviewPartial extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('8.1.0');
    }

    public function update()
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => resource_path('/views'),
        ]);

        collect($disk->allFiles())
            ->filter(function($file) use ($disk) {
                return Str::contains($disk->get($file), '{{ partial:statamic-peak-tools::snippets/live_preview }}');
            })
            ->each(function ($file) use ($disk) {
                $contents = Str::of($disk->get($file))
                    ->replace("{{ partial:statamic-peak-tools::snippets/live_preview }}", "" );

                $disk->put($file, $contents);

                $this->console()->info("Removed `{{ partial:statamic-peak-tools::snippets/live_preview }}` from `$file`.");
            }
        );
    }
}
