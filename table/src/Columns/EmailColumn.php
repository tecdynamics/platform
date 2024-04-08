<?php

namespace Tec\Table\Columns;

use Tec\Base\Facades\Html;
use Tec\Table\Columns\Concerns\HasLink;
use Tec\Table\Contracts\FormattedColumn as FormattedColumnContract;

class EmailColumn extends FormattedColumn implements FormattedColumnContract
{
    use HasLink;

    public static function make(array|string $data = [], string $name = ''): static
    {
        return parent::make($data ?: 'email', $name)
            ->title(trans('core/base::tables.email'))
            ->alignStart();
    }

    public function formattedValue($value): string|null
    {
        if (! $this->isLinkable() || ! $value) {
            return null;
        }

        return Html::mailto($value, $value);
    }
}
