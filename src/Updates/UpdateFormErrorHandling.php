<?php

namespace Studio1902\PeakTools\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class UpdateFormErrorHandling extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.0.3');
    }

    public function update()
    {
        $view = base_path("resources/views/page_builder/_form.antlers.html");

        if (File::exists($view)) {
            $contents = Str::of(File::get($view))
                ->replace('x-if="form.hasErrors"', 'x-if="form.hasErrors && submitted"');

            File::put($view, $contents);

            $this->console()->info('Replaced `x-if="form.hasErrors` with `x-if="form.hasErrors && submitted"` in `/views/page_builder/_form.antlers.html`. Update this manually for any other form files you may have.');
        } else {
            $this->console()->info('Replace `x-if="form.hasErrors"` with `x-if="form.hasErrors && submitted"`  in all your form partials.');
        }
    }
}
