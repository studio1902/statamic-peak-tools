<?php

namespace Studio1902\PeakTools\Updates;

use Statamic\Facades\GlobalSet;
use Statamic\UpdateScripts\UpdateScript;

class RemoveGlobalThemeOptions extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('9.2.0');
    }

    public function update()
    {
        $blueprint = GlobalSet::findByHandle('browser_appearance')->blueprint();
        $contents = $blueprint->contents();
        $sections = $contents['tabs']['general']['sections'] ?? [];

        foreach ($sections as $handle => $section) {
            if (($section['display'] ?? null) === 'Theme') {
                unset($sections[$handle]);
            }
        }

        $contents['tabs']['general']['sections'] = $sections;

        $blueprint->setContents($contents);
        $blueprint->save();

        $this->console()->info('Removed theming options from the browser appearance global.');
    }
}
