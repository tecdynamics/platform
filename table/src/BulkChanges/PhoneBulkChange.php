<?php

namespace Tec\Table\BulkChanges;

use Tec\Base\Facades\BaseHelper;
use Tec\Table\Abstracts\TableBulkChangeAbstract;

class PhoneBulkChange extends TableBulkChangeAbstract
{
    public static function make(array $data = []): static
    {
        return parent::make()
            ->name('phone')
            ->title(trans('core/base::tables.phone'))
            ->type('text')
            ->validate('required|' . BaseHelper::getPhoneValidationRule());
    }
}
