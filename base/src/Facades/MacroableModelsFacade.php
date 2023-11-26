<?php

namespace Tec\Base\Facades;

use Tec\Base\Supports\MacroableModels;
use Illuminate\Support\Facades\Facade;
/**
 * @method static array getAllMacros()
 * @method static mixed getMacro(string $name)
 * @method static bool removeMacro(string $model, string $name)
 * @method static array modelsThatImplement(string $name)
 * @method static array macrosForModel(string $model)
 *
 * @see \Tec\Base\Supports\MacroableModels
 */
class MacroableModelsFacade extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MacroableModels::class;
    }
}
