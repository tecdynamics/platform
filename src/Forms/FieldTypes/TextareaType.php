<?php

namespace Tec\Base\Forms\FieldTypes;

use Tec\Base\Traits\Forms\CanSpanColumns;

class TextareaType extends \Kris\LaravelFormBuilder\Fields\TextareaType
{
    use CanSpanColumns;
}
