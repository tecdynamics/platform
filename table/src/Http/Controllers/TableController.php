<?php

namespace Tec\Table\Http\Controllers;

use Tec\Base\Http\Controllers\BaseController;
use Tec\Table\TableBuilder;

class TableController extends BaseController
{
    public function __construct(protected TableBuilder $tableBuilder)
    {
    }
}
