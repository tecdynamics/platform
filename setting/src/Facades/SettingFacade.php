<?php

namespace Tec\Setting\Facades;

use Illuminate\Support\Facades\Facade;
use Tec\Setting\Supports\SettingStore;

class SettingFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SettingStore::class;
    }
}
