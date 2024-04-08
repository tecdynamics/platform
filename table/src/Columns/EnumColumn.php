<?php

namespace Tec\Table\Columns;

use BackedEnum;
use Tec\Base\Supports\Enum;
use Tec\Table\Contracts\FormattedColumn as FormattedColumnContract;

class EnumColumn extends FormattedColumn implements FormattedColumnContract
{
    public static function make(array|string $data = [], string $name = ''): static
    {
        return parent::make($data, $name)
            ->alignCenter()
            ->width(100)
            ->renderUsing(function (EnumColumn $column, $value) {
                return $column->formattedValue($value);
            });
    }

    public function formattedValue($value): string|null
    {
        if (! $value instanceof Enum && ! $value instanceof BackedEnum) {
            return '';
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        $table = $this->getTable();

        if ($table->isExportingToExcel() || $table->isExportingToCSV()) {
            return $value->getValue();
        }

        return $value->toHtml() ?: $value->getValue();
    }
}
