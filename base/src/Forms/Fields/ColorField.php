<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Facades\Assets;
use Tec\Base\Forms\FormField;

class ColorField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScripts(['colorpicker'])
            ->addStyles(['colorpicker']);

        return 'core/base::forms.fields.color';
    }
}
