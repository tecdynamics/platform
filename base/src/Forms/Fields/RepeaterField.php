<?php

namespace Tec\Base\Forms\Fields;

class RepeaterField extends SelectType
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.repeater';
    }
}
