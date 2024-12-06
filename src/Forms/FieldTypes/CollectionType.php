<?php

namespace Tec\Base\Forms\FieldTypes;

use Tec\Base\Traits\Forms\CanSpanColumns;

class CollectionType extends \Kris\LaravelFormBuilder\Fields\CollectionType
{
    use CanSpanColumns;
}
