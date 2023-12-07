<?php

namespace Tec\Dashboard\Repositories\Interfaces;

use Tec\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface DashboardWidgetSettingInterface extends RepositoryInterface
{
    public function getListWidget(): Collection;
}
