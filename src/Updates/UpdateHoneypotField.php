<?php

namespace Studio1902\PeakTools\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class UpdateHoneypotField extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.4.2');
    }

    public function update()
    {
        $view = base_path("resources/views/page_builder/_form.antlers.html");

        if (File::exists($view)) {
            $contents = Str::of(File::get($view))
                ->replace('name="{{ honeypot }}"', 'name="{{ honeypot }}" x-model="form.{{ honeypot }}"');

            File::put($view, $contents);

            $this->console()->info('Added `x-model="form.{{ honeypot }}"` to the honeypot input field in `/views/page_builder/_form.antlers.html`. Update this manually for any other form files you may have.');
        } else {
            $this->console()->info('add `x-model="form.{{ honeypot }}"` to the honeypot field in all your form partials.');
        }
    }
}
