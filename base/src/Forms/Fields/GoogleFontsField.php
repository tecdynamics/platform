<?php

namespace Tec\Base\Forms\Fields;

class GoogleFontsField extends SelectType
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.google-fonts';
    }
}
