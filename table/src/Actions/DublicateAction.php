<?php

namespace Tec\Table\Actions;

class DublicateAction extends Action
{
    public static function make(string $name = 'duplicate'): static
    {
        return parent::make($name)
            ->label('Duplicate')
            ->color('btn-success')
            ->attributes(['id'=>'duplicate'])
            ->icon('fa fa-clone');
    }
}
