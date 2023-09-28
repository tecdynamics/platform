<?php

namespace Tec\Dashboard\Repositories\Interfaces;

use Tec\Support\Repositories\Interfaces\RepositoryInterface;

interface DashboardWidgetSettingInterface extends RepositoryInterface
{
    /**
     * @return mixed
     *
     * @since 2.1
     */
    public function getListWidget();
}
