<?php

namespace Studio1902\PeakTools\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Statamic\UpdateScripts\UpdateScript;

class UpdateButtonAttributeTags extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.1.0');
    }

    public function update()
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => resource_path('/views'),
        ]);

        collect($disk->allFiles())
            ->filter(function($file) use ($disk) {
                return Str::contains($disk->get($file), 'partial:snippets/button_attributes');
            })
            ->each(function ($file) use ($disk) {
                $contents = Str::of($disk->get($file))
                    ->replace("partial:snippets/button_attributes", "partial:statamic-peak-tools::snippets/button_attributes" );

                $disk->put($file, $contents);

                $this->console()->info("Replaced `partial:snippets/button_attributes` with `partial:statamic-peak-tools::snippets/button_attributes` in `$file`.");
            }
        );

        $this->console()->info("You can delete the `views/snippets/_button_attributes.antlers.html` partial as it is now pulled in from the Statamic Peak Tools addon.");
    }
}
