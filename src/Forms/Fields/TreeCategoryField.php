<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Forms\FormField;

class TreeCategoryField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.tree-categories';
    }
}
