<?php

namespace Studio1902\PeakTools\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class UpdateFormFields extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('4.0.2');
    }

    public function update()
    {
        $views = [
            base_path("resources/views/vendor/statamic/forms/fields/assets.antlers.html"),
            base_path("resources/views/vendor/statamic/forms/fields/checkboxes.antlers.html"),
            base_path("resources/views/vendor/statamic/forms/fields/default.antlers.html"),
            base_path("resources/views/vendor/statamic/forms/fields/radio.antlers.html"),
            base_path("resources/views/vendor/statamic/forms/fields/select.antlers.html"),
            base_path("resources/views/vendor/statamic/forms/fields/textarea.antlers.html"),
            base_path("resources/views/vendor/statamic/forms/fields/toggle.antlers.html")
        ];

        foreach($views as $view) {
            if (File::exists($view)) {
                $contents = Str::of(File::get($view))
                    ->remove("{{ js_attributes }}\n");

                File::put($view, $contents);

                $this->console()->info("Removed `{{ js_attributes }}` from `$view`.");
            } else {
                $this->console()->info("Remove `{{ js_attributes }}` from `$view` if you use one.");
            }
        }

        $views = [
            base_path("resources/views/vendor/statamic/forms/fields/checkboxes.antlers.html"),
            base_path("resources/views/vendor/statamic/forms/fields/radio.antlers.html"),
        ];

        foreach($views as $view) {
            if (File::exists($view)) {
                $contents = Str::of(File::get($view))
                    ->remove("x-model=\"form.{{ handle }}\"\n")
                    ->remove("@change=\"form.validate('{{ handle }}')\"\n")
                    ->replace("<input", "<input\nx-model=\"form.{{ handle }}\"\n@change=\"form.validate('{{ handle }}')\"");

                File::put($view, $contents);

                $this->console()->info("Moved `x-model and @change` in `$view`.");
            } else {
                $this->console()->info("Move `x-model and @change` to the input element in `$view` if you use one.");
            }
        }

        $view = base_path("resources/views/vendor/statamic/forms/fields/assets.antlers.html");

        if (File::exists($view)) {
            $contents = Str::of(File::get($view))
                ->replace("@change=\"form.validate('{{ handle }}')\"", "@change=\"form.{{ handle }} = Object.values(\$event.target.files)\"")
                ->remove("x-model=\"form.{{ handle }}\"\n");

            File::put($view, $contents);

            $this->console()->info("Removed `x-model` and replaced `@change` from `$view`.");
        } else {
            $this->console()->info("Remove `x-model=\"form.{{ handle }}\"` and replace `@change=\"form.validate('{{ handle }}')\"` with `@change=\"form.{{ handle }} = Object.values(\$event.target.files)\"` from `$view` if you use one.");
        }
    }
}
