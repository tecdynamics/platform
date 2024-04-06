<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Forms\FieldTypes\SelectType;

class UiSelectorField extends SelectType
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.ui-selector';
    }
}
