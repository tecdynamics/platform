<?php

namespace Tec\Base\Forms;

use Tec\Base\Traits\Forms\CanSpanColumns;
use Kris\LaravelFormBuilder\Fields\FormField as BaseFormField;

abstract class FormField extends BaseFormField
{
    use CanSpanColumns;
}
