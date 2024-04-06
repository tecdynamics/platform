<?php

namespace Tec\Base\Facades;

use Tec\Base\Helpers\AdminHelper as AdminHelperSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Routing\RouteRegistrar registerRoutes(\Closure|callable $closure, array $middleware = ['web','core','auth'])
 * @method static bool isInAdmin(bool $force = false)
 * @method static string themeMode()
 *
 * @see \Tec\Base\Helpers\AdminHelper
 */
class AdminHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AdminHelperSupport::class;
    }
}
