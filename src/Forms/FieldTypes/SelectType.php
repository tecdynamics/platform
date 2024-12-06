<?php

namespace Tec\Base\Forms\FieldTypes;

use Tec\Base\Traits\Forms\CanSpanColumns;

class SelectType extends \Kris\LaravelFormBuilder\Fields\SelectType
{
    use CanSpanColumns;
}
