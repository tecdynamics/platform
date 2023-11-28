<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Forms\FormField;

class DatePickerField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.date-picker';
    }
}
