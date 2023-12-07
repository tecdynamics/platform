<?php

namespace Tec\Base\Facades;

use Tec\Base\Supports\DashboardMenu as DashboardMenuSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Tec\Base\Supports\DashboardMenu make()
 * @method static \Tec\Base\Supports\DashboardMenu registerItem(array $options)
 * @method static \Tec\Base\Supports\DashboardMenu removeItem(array|string $id, $parentId = null)
 * @method static bool hasItem(string $id, string|null $parentId = null)
 * @method static \Illuminate\Support\Collection getAll()
 * @method static \Tec\Base\Supports\DashboardMenu tap(callable|null $callback = null)
 * @method static \Tec\Base\Supports\DashboardMenu|mixed when(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \Tec\Base\Supports\DashboardMenu|mixed unless(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 *
 * @see \Tec\Base\Supports\DashboardMenu
 */
class DashboardMenu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DashboardMenuSupport::class;
    }
}
