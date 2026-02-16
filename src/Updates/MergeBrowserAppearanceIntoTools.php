<?php

namespace Studio1902\PeakTools\Updates;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\error;
use function Laravel\Prompts\spin;

class MergeBrowserAppearanceIntoTools extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('9.0.0');
    }

    public function update()
    {
        $blueprint = base_path("resources/blueprints/globals/browser_appearance.yaml");

        if (File::exists($blueprint)) {
            $contents = Str::of(File::get($blueprint))
                ->replace('statamic-peak-browser-appearance::', 'statamic-peak-tools::');

            File::put($blueprint, $contents);

            $this->console()->info('Updated blueprint import references');
        } else {
            $this->console()->info('Update any blueprint import references you might have, from `statamic-peak-browser-appearance::` to `statamic-peak-tools::`.');
        }

        $disk = Storage::build([
            'driver' => 'local',
            'root' => resource_path('/views'),
        ]);

        collect($disk->allFiles())
            ->filter(function($file) use ($disk) {
                return Str::contains($disk->get($file), 'partial:statamic-peak-browser-appearance::');
            })
            ->each(function ($file) use ($disk) {
                $contents = Str::of($disk->get($file))
                    ->replace("partial:statamic-peak-browser-appearance::", "partial:statamic-peak-tools::" );

                $disk->put($file, $contents);

                $this->console()->info("Replaced `partial:statamic-peak-browser-appearance::` with `partial:statamic-peak-tools::` in `$file`.");
            }
        );


        $this->run(
            command: 'composer remove studio1902/statamic-peak-browser-appearance',
            processingMessage: 'Removing package `studio1902/statamic-peak-browser-appearance`. This may take a while.',
            successMessage: 'Package `studio1902/statamic-peak-browser-appearance` removed.',
            timeout: 600
        );
    }

    protected function run(string $command, string $processingMessage = '', string $successMessage = '', ?string $errorMessage = null, bool $tty = false, bool $spinner = true, int $timeout = 120): bool
    {
        $process = new Process(explode(' ', $command));
        $process->setTimeout($timeout);

        if ($tty) {
            $process->setTty(true);
        }

        try {
            $spinner ?
                $this->withSpinner(
                    fn () => $process->mustRun(),
                    $processingMessage,
                    $successMessage
                ) :
                $this->withoutSpinner(
                    fn () => $process->mustRun(),
                    $successMessage
                );

            return true;
        } catch (ProcessFailedException $exception) {
            error($errorMessage ?? $exception->getMessage());

            return false;
        }
    }

    protected function withSpinner(callable $callback, string $processingMessage = '', string $successMessage = ''): void
    {
        spin($callback, $processingMessage);

        if ($successMessage) {
            info("[✓] $successMessage");
        }
    }

     protected function withoutSpinner(callable $callback, string $successMessage = ''): void
    {
        $callback();

        if ($successMessage) {
            info("[✓] $successMessage");
        }
    }
}
