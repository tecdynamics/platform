<?php

namespace Tec\Base\Facades;

use Tec\Base\Supports\Assets as BaseAssets;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void setConfig(array $config)
 * @method static array getThemes()
 * @method static string renderHeader($lastStyles = [])
 * @method static string renderFooter()
 * @method static \Tec\Base\Supports\Assets usingVueJS()
 * @method static \Tec\Base\Supports\Assets disableVueJS()
 * @method static \Tec\Assets\Assets addScripts(string|array $assets)
 * @method static \Tec\Assets\Assets addStyles(string|array $assets)
 * @method static \Tec\Assets\Assets addStylesDirectly(array|string $assets)
 * @method static \Tec\Assets\Assets addScriptsDirectly(string|array $assets, string $location = 'footer')
 * @method static \Tec\Assets\Assets removeStyles(string|array $assets)
 * @method static \Tec\Assets\Assets removeScripts(string|array $assets)
 * @method static \Tec\Assets\Assets removeItemDirectly(string|array $assets, string|null $location = null)
 * @method static array getScripts(string|null $location = null)
 * @method static array getStyles(array $lastStyles = [])
 * @method static string|null scriptToHtml(string $name)
 * @method static string|null styleToHtml(string $name)
 * @method static string getBuildVersion()
 * @method static \Tec\Assets\HtmlBuilder getHtmlBuilder()
 *
 * @see \Tec\Base\Supports\Assets
 */
class Assets extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseAssets::class;
    }
}
