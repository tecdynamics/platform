<?php

namespace Tec\Base\Facades;

use Tec\Base\Supports\Filter as BaseFilter;
use Illuminate\Support\Facades\Facade;

class FilterFacade extends Facade
{

    /**
     * @return string
     * @since 2.1
     */
    protected static function getFacadeAccessor()
    {
        return BaseFilter::class;
        return 'core:filter';
    }
}
