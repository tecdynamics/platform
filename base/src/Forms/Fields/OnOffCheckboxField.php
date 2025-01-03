<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Forms\FieldTypes\FormField;

class OnOffCheckboxField extends FormField
{
    protected bool $useDefaultFieldClass = false;

    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.on-off-checkbox';
    }
}
