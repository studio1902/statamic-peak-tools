<?php

namespace Studio1902\PeakTools\Updates;

use Statamic\Support\Arr;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use Statamic\UpdateScripts\UpdateScript;

class UpdateImagesBlueprintWithExemptToggle extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.4.0');
    }

    public function update()
    {
        $file = base_path("resources/blueprints/assets/images.yaml");

        if (! File::exists($file)) {
            $this->console()->info('Add an `exempt_from_alt` toggle field to any asset blueprints where you want this functionality.');

            return;
        }

        $newField = [
            'handle' => 'exempt_from_alt',
            'field' => [
                'inline_label' => "This image doesn't need an alt text.",
                'default' => false,
                'type' => 'toggle',
                'display' => 'Exempt',
                'icon' => 'toggle',
                'listable' => 'hidden',
                'instructions_position' => 'below',
                'visibility' => 'visible',
                'replicator_preview' => true,
                'hide_display' => false,
                'instructions' => "When enabled, this image won't show up in the control panel dashboard as an image that misses alt text. Images without an alt text will automatically be hidden for screen readers."
            ]
        ];

        $blueprint = Yaml::parseFile($file);
        $existingFields = Arr::get($blueprint, 'sections.main.fields');

        if (! $existingFields) {
            $this->console()->info("Couldn't add an exempt from alt toggle to the images blueprint in `resources/blueprints/assets/images.yaml`");

            return;
        }

        $existingFields[] = $newField;

        Arr::set($blueprint, 'sections.main.fields', $existingFields);
        File::put($file, Yaml::dump($blueprint, 99, 2));

        $this->console()->info('Added an exempt from alt toggle to the images blueprint in `resources/blueprints/assets/images.yaml`');
    }
}
