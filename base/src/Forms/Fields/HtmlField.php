<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Forms\FormField;

class HtmlField extends FormField
{
    protected function getDefaults(): array
    {
        return [
            'html' => '',
            'wrapper' => false,
            'label_show' => false,
        ];
    }

    public function getAllAttributes(): array
    {
        // No input allowed for html fields.
        return [];
    }

    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.html';
    }
}
