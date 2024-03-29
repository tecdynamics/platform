<?php

use Tec\Base\Facades\BaseHelper;
use Tec\Base\Facades\DashboardMenu;
use Tec\Base\Facades\Html;
use Tec\Base\Facades\PageTitle;
use Tec\Base\Supports\Core;
use Tec\Base\Supports\DashboardMenu as DashboardMenuSupport;
use Tec\Base\Supports\Editor;
use Tec\Base\Supports\PageTitle as PageTitleSupport;

if (! function_exists('language_flag')) {
    function language_flag(string $flag, string|null $name = null, int $width = 16): string
    {
        return Html::image(
            asset(BASE_LANGUAGE_FLAG_PATH . $flag . '.svg'),
            $name,
            ['title' => $name, 'width' => $width]
        );
    }
}

if (! function_exists('render_editor')) {
    function render_editor(
        string $name,
        string|null $value = null,
        bool $withShortCode = false,
        array $attributes = []
    ): string {
        return (new Editor())->registerAssets()->render($name, $value, $withShortCode, $attributes);
    }
}

if (! function_exists('is_in_admin')) {
    function is_in_admin(bool $force = false): bool
    {
        $prefix = BaseHelper::getAdminPrefix();

        if (empty($prefix)) {
            return true;
        }

        $segments = array_slice(request()->segments(), 0, count(explode('/', $prefix)));

        $isInAdmin = implode('/', $segments) === $prefix;

        return $force ? $isInAdmin : apply_filters(IS_IN_ADMIN_FILTER, $isInAdmin);
    }
}

if (! function_exists('page_title')) {
    function page_title(): PageTitleSupport
    {
        return PageTitle::getFacadeRoot();
    }
}

if (! function_exists('dashboard_menu')) {
    function dashboard_menu(): DashboardMenuSupport
    {
        return DashboardMenu::getFacadeRoot();
    }
}

if (! function_exists('get_cms_version')) {
    function get_cms_version(): string
    {
        try {
            return Core::make()->version();
        } catch (Throwable) {
            return '...';
        }
    }
}

if (! function_exists('get_core_version')) {
    function get_core_version(): string
    {
        return '6.10.4';
    }
}

if (! function_exists('get_minimum_php_version')) {
    function get_minimum_php_version(): string
    {
        try {
            return Core::make()->minimumPhpVersion();
        } catch (Throwable) {
            return phpversion();
        }
    }
}

if (! function_exists('platform_path')) {
    function platform_path(string|null $path = null): string
    {
        return base_path('platform/' . $path);
    }
}

if (! function_exists('core_path')) {
    function core_path(string|null $path = null): string
    {
        return platform_path('core/' . $path);
    }
}

if (! function_exists('package_path')) {
    function package_path(string|null $path = null): string
    {
        return platform_path('packages/' . $path);
    }
}
