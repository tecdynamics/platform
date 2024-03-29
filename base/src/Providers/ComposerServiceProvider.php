<?php

namespace Tec\Base\Providers;

use Tec\ACL\Models\UserMeta;
use Tec\Base\Facades\Assets;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Supports\ServiceProvider;
use Tec\Media\Facades\RvMedia;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot(Factory $view): void
    {
        $view->composer(['core/base::layouts.partials.top-header', 'core/acl::auth.master'], function (View $view) {
            $themes = Assets::getThemes();

            $defaultTheme = setting('default_admin_theme', config('core.base.general.default-theme'));

            if (Auth::guard()->check() && ! session()->has('admin-theme') && ! BaseHelper::hasDemoModeEnabled()) {
                $activeTheme = UserMeta::getMeta('admin-theme', $defaultTheme);
            } elseif (session()->has('admin-theme')) {
                $activeTheme = session('admin-theme');
            } else {
                $activeTheme = $defaultTheme;
            }

            if (! array_key_exists($activeTheme, $themes)) {
                $activeTheme = $defaultTheme;
            }

            if (array_key_exists($activeTheme, $themes)) {
                Assets::addStylesDirectly($themes[$activeTheme]);
            }

            session(['admin-theme' => $activeTheme]);

            $view->with(compact('themes', 'activeTheme'));
        });

        $view->composer(['core/media::config'], function () {
            $mediaPermissions = RvMedia::getConfig('permissions', []);
            if (Auth::guard()->check() && ! Auth::guard()->user()->isSuperUser() && Auth::guard()->user()->permissions) {
                $mediaPermissions = array_intersect(array_keys(Auth::guard()->user()->permissions), $mediaPermissions);
            }

            RvMedia::setPermissions($mediaPermissions);
        });
    }
}
