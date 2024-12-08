<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Forms\FormField;

class ColorField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.color';
    }
}
