<?php

namespace Tec\Base\Facades;

use Illuminate\Support\Facades\Facade;
use Tec\Base\Supports\Action as ActionFacade;

/**
 * @method static void fire(string $action, array $args)
 * @method static void addListener(array|string|null $hook, \Closure|array|string $callback, int $priority = 20, int $arguments = 1)
 * @method static \Tec\Base\Supports\ActionHookEvent removeListener(string $hook)
 * @method static array getListeners()
 *
 * @see \Tec\Base\Supports\Action
 */
class Action extends Facade
{

    /**
     * @return string
     * @since 2.1
     */
    protected static function getFacadeAccessor()
    {
        return ActionFacade::class;
    }
}