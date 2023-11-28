<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Facades\Assets;
use Tec\Base\Forms\FormField;

class TimeField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScripts(['timepicker'])
            ->addStyles(['timepicker']);

        return 'core/base::forms.fields.time';
    }
}
