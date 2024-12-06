<?php

namespace Tec\Base\Listeners;

use Tec\ACL\Models\User;
use Tec\Base\Facades\DashboardMenu;
use Illuminate\Auth\Events\Login;

class ClearDashboardMenuCachesForLoggedUser
{
    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        DashboardMenu::default()->clearCachesForCurrentUser();
    }
}
