<?php

namespace Studio1902\PeakTools\FormConnectors;

use Override;

class PeakEmail extends \Statamic\Forms\Connectors\Email
{
    protected $developer = 'Studio 1902';

    #[Override]
    public function blueprint(): \Statamic\Fields\Blueprint
    {
        $blueprint = parent::blueprint();

        $mailerField = $blueprint->field('mailer');

        $blueprint
            ->removeField('html')
            ->removeField('text')
            ->removeField('markdown')
            ->removeField('mailer')
            ->ensureField('show_submitted_data', [
                'type' => 'toggle',
                'display' => 'Show submitted data',
                'instructions' => 'Whether to list the submitted data in the email.',
                'width' => 50,
                'default' => false,
            ])
            ->ensureField('html', [
                'type' => 'hidden',
                'default' => 'peak-tools-email::form',
            ])
            ->ensureField('text', [
                'type' => 'hidden',
                'default' => 'peak-tools-email::form_text',
            ])
            ->ensureField('mailer', $mailerField->config());

        return $blueprint;
    }

    #[Override]
    protected function connectionRules(): array
    {
        return array_merge(parent::connectionRules(), [
            'html' => 'nullable',
            'text' => 'nullable',
        ]);
    }
}
