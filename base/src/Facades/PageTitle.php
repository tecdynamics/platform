<?php

namespace Tec\Base\Facades;

use Illuminate\Support\Facades\Facade;
use Tec\Base\Supports\PageTitle as PageTitleSupport;

/**
 * @method static void setTitle(string $title)
 * @method static string|null getTitle(bool $full = true)
 *
 * @see \Tec\Base\Supports\PageTitle
 */
class PageTitle extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PageTitleSupport::class;
    }
}
