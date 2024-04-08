<?php

namespace Tec\Table\Contracts;

use Tec\Base\Contracts\BaseModel;
use Tec\Table\Abstracts\TableAbstract;

interface FormattedColumn
{
    public function formattedValue($value): string|null;

    public function renderCell(BaseModel|array $item, TableAbstract $table): string;
}
