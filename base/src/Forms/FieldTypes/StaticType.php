<?php

namespace Tec\Base\Forms\FieldTypes;

use Tec\Base\Traits\Forms\CanSpanColumns;

class StaticType extends \Kris\LaravelFormBuilder\Fields\StaticType
{
    use CanSpanColumns;
}
