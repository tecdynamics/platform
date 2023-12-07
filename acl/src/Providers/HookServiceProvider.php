<?php

namespace Tec\ACL\Providers;

use Tec\ACL\Hooks\UserWidgetHook;
use Tec\Base\Supports\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [UserWidgetHook::class, 'addUserStatsWidget'], 12, 2);
    }
}
