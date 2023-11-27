<?php

namespace Tec\Table\Columns;

use Tec\Base\Facades\BaseHelper;
use Tec\Table\Contracts\FormattedColumn;

class DateColumn extends Column implements FormattedColumn
{
    protected string $dateFormat;

    public static function make(array|string $data = [], string $name = ''): static
    {
        return parent::make($data, $name)
            ->type('date')
            ->width(100)
            ->withEmptyState();
    }

    public function dateFormat(string $format): static
    {
        $this->dateFormat = $format;

        return $this;
    }

    public function editedFormat($value): string
    {
        if (! $value) {
            return '';
        }

        return BaseHelper::formatDate($value, $this->dateFormat ?? BaseHelper::getDateFormat());
    }
}
