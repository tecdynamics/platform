<?php

namespace Tec\Base\Forms\Fields;

class AutocompleteField extends SelectType
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.autocomplete';
    }
}
