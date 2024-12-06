<?php

namespace Tec\Dashboard\Providers;

use Tec\Base\Facades\DashboardMenu;
use Tec\Base\Supports\ServiceProvider;
use Tec\Base\Traits\LoadAndPublishDataTrait;
use Tec\Dashboard\Models\DashboardWidget;
use Tec\Dashboard\Models\DashboardWidgetSetting;
use Tec\Dashboard\Repositories\Eloquent\DashboardWidgetRepository;
use Tec\Dashboard\Repositories\Eloquent\DashboardWidgetSettingRepository;
use Tec\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Tec\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;

/**
 * @since 02/07/2016 09:50 AM
 */
class DashboardServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(DashboardWidgetInterface::class, function () {
            return new DashboardWidgetRepository(new DashboardWidget());
        });

        $this->app->bind(DashboardWidgetSettingInterface::class, function () {
            return new DashboardWidgetSettingRepository(new DashboardWidgetSetting());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('core/dashboard')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadMigrations();

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-core-dashboard',
                    'priority' => -9999,
                    'name' => 'core/base::layouts.dashboard',
                    'icon' => 'ti ti-home',
                    'route' => 'dashboard.index',
                    'permissions' => false,
                ]);
        });
    }
}
