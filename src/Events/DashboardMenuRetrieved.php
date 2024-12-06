<?php

namespace Tec\Base\Events;

use Tec\Base\Supports\DashboardMenu;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Collection;

class DashboardMenuRetrieved
{
    use Dispatchable;

    public function __construct(
        public DashboardMenu $dashboardMenu,
        public Collection $menuItems
    ) {
    }
}
