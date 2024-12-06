<?php

namespace Tec\Table\Actions;

class DouplicateAction extends Action
{
    public static function make(string $name = 'duplicate'): static
    {
        return parent::make($name)
            ->label('Duplicate Entry')
            ->color('info')
            ->icon('ti ti-files')
            ->confirmation()
            ->confirmationModalTitle('Duplicate Entry')
            ->confirmationModalMessage('Duplicate Entry')
            ->confirmationModalButton('Duplicate');
    }
}
