<?php

namespace Tec\Base\Facades;

use Tec\Base\Supports\Breadcrumb as BreadcrumbSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Tec\Base\Supports\Breadcrumb for(string $group)
 * @method static \Tec\Base\Supports\Breadcrumb default()
 * @method static static add(string $label, string $url = '')
 * @method static static prepend(string $label, string $url = '')
 * @method static \Illuminate\Support\Collection getItems()
 * @method static string render()
 * @method static string toHtml()
 * @method static static renderUsing(\Closure $renderUsingCallback)
 * @method static static beforeRendering(\Closure $beforeRenderCallback)
 * @method static static afterRendering(\Closure $afterRenderCallback)
 * @method static mixed|null rendering(\Closure|string $content)
 *
 * @see \Tec\Base\Supports\Breadcrumb
 */
class Breadcrumb extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BreadcrumbSupport::class;
    }
}
