<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Facades\Assets;
use Tec\Base\Forms\FormField;

class TagField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addStyles('tagify')
            ->addScripts('tagify')
            ->addScriptsDirectly('vendor/core/core/base/js/tags.js');

        return 'core/base::forms.fields.tags';
    }
}
