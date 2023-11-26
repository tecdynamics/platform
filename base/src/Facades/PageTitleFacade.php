<?php

namespace Tec\Base\Facades;

use Tec\Base\Supports\PageTitle;
use Illuminate\Support\Facades\Facade;
/**
 * @method static void setTitle(string $title)
 * @method static string|null getTitle(bool $full = true)
 *
 * @see \Tec\Base\Supports\PageTitle
 */
class PageTitleFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PageTitle::class;
    }
}
