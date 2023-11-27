<?php

namespace Tec\Table\Contracts;

interface FormattedColumn
{
    public function editedFormat($value): string|null;
}
