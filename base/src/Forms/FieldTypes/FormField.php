<?php

namespace Tec\Base\Forms\FieldTypes;

use Tec\Base\Traits\Forms\CanSpanColumns;

abstract class FormField extends \Kris\LaravelFormBuilder\Fields\FormField
{
    use CanSpanColumns;
}
