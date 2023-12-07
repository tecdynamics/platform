<?php

namespace Tec\Base\Facades;

use Illuminate\Support\Facades\Facade;
use Tec\Base\Supports\PageTitle as PageTitleSupport;

/**
 * @method static void setTitle(string $title)
 * @method static string|null getTitle(bool $full = true)
 * @deprecated
 * @see \Tec\Base\Supports\PageTitle
 */
class PageTitleFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PageTitleSupport::class;
    }
}
