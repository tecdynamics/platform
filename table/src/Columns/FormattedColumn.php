<?php

namespace Tec\Table\Columns;

use Tec\Table\Contracts\FormattedColumn as EditedColumnContract;

class FormattedColumn extends Column implements EditedColumnContract
{
    public function editedFormat($value): string|null
    {
        return $value;
    }
}
