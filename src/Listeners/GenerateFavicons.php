<?php

namespace Studio1902\PeakTools\Listeners;

use Statamic\Events\GlobalVariablesSaved;
use Statamic\Globals\Variables;
use Studio1902\PeakTools\Generators\Favicons;

class GenerateFavicons
{
    private function shouldHandle(Variables $variables): bool
    {
        return $variables->globalSet()->handle() === 'browser_appearance';
    }

    public function handle(GlobalVariablesSaved $event): void
    {
        /** @var Variables $globals */
        $variables = $event->variables;

        if (!$this->shouldHandle($variables)) {
            return;
        }

        dispatch(fn () => Favicons::generate())->afterResponse();
    }
}
