<?php

namespace Tec\Table\Columns;

use Tec\Base\Models\BaseModel;
use Tec\Table\Contracts\FormattedColumn;

class IdColumn extends Column implements FormattedColumn
{
    public static function make(array|string $data = [], string $name = ''): static
    {
        return parent::make($data ?: 'id', $name)
            ->title(trans('core/base::tables.id'))
            ->alignCenter()
            ->width(20)
            ->columnVisibility();
    }

    public function editedFormat($value): string|null
    {
        return $this
            ->when(BaseModel::getTypeOfId() !== 'BIGINT', function (IdColumn $column) {
                return $column->limit();
            })
            ->applyLimitIfAvailable($value);
    }
}
