<?php

namespace Studio1902\PeakTools\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class UpdateFormJSDriver extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.0.1');
    }

    public function update()
    {
        $view = base_path("resources/views/page_builder/_form.antlers.html");

        if (File::exists($view)) {
            $contents = Str::of(File::get($view))
                ->replace('js="alpine:dynamic_form"', 'js="alpine:form"');

            File::put($view, $contents);

            $this->console()->info('Replaced `js="alpine:dynamic_form"` with `js="alpine:form"` in `/views/page_builder/_form.antlers.html`. Update this manually for any other form files you may have.');
        } else {
            $this->console()->info('Replace `js="alpine:dynamic_form"` with `js="alpine:form"`  in all your form partials.');
        }
    }
}
