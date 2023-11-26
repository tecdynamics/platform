<?php

namespace Tec\Media\Facades;

use Tec\Media\RvMedia as BaseRvMedia;
use Illuminate\Support\Facades\Facade;

class RvMediaFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BaseRvMedia::class;
    }
}
