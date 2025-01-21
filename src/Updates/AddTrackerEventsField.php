<?php

namespace Studio1902\PeakTools\Updates;

use Statamic\Support\Arr;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use Statamic\UpdateScripts\UpdateScript;

class AddTrackerEventsField extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('5.3.0');
    }

    public function update()
    {
        $file = base_path("resources/fieldsets/button.yaml");

        if (File::exists($file)) {
            $newField = [
                'handle' => 'tracker_event',
                'field' => [
                    'input_type' => 'text',
                    'type' => 'text',
                    'localizable' => true,
                    'listable' => false,
                    'placeholder' => 'my-custom-event',
                    'display' => 'Tracker event',
                    'instructions' => "Add the name of the event you want to be fired on click. These events only fire on a production environment. Allowed: `A-Z`, `a-z`, `0-9`, `-`, `_`.",
                    'validate' => ['regex:/[A-Za-z0-9_\-]+$/'],
                    'instructions_position' => 'below',
                    'width' => 50,
                    'replicator_preview' => false,
                    'visibility' => 'visible',
                    'antlers' => false,
                    'hide_display' => false,
                    'if' => [
                      'show_controls' => 'equals true',
                      'label' => 'custom tracker_events'
                    ]
                ]
            ];

            $fieldset = Yaml::parseFile($file);
            $existingFields = Arr::get($fieldset, 'fields');

            $existingFields[] = $newField;

            Arr::set($fieldset, 'fields', $existingFields);
            File::put($file, Yaml::dump($fieldset, 99, 2));

            $this->console()->info('Added `tracker_event` field to the button fieldset to handle custom events.');
        }
    }
}
