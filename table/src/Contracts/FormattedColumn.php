<?php

namespace Tec\Table\Contracts;

use Tec\Base\Contracts\BaseModel;
use Tec\Table\Abstracts\TableAbstract;
use stdClass;

interface FormattedColumn
{
    public function formattedValue($value): ?string;

    public function renderCell(BaseModel|stdClass|array $item, TableAbstract $table): string;
}
