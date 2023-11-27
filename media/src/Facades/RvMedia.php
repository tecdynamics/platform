<?php

namespace Tec\Media\Facades;

use Illuminate\Support\Facades\Facade;
use Tec\Media\RvMedia as BaseRvMedia;

class RvMedia extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BaseRvMedia::class;
    }
}
