<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Forms\FieldTypes\FormField;

class OnOffField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.on-off';
    }

    public function getDefaults(): array
    {
        return [
            ...parent::getDefaults(),
            'attr' => ['class' => null, 'id' => $this->getName()],
        ];
    }
}
