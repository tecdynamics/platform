<?php

namespace Tec\ACL\Providers;

use Tec\ACL\Hooks\UserWidgetHook;
use Tec\Base\Supports\ServiceProvider;
use Tec\Dashboard\Events\RenderingDashboardWidgets;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['events']->listen(RenderingDashboardWidgets::class, function () {
            add_filter(DASHBOARD_FILTER_ADMIN_LIST, [UserWidgetHook::class, 'addUserStatsWidget'], 12, 2);
        });
    }
}
