<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Forms\FormField;

class OnOffField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.on-off';
    }
}
