<?php

namespace Tec\Dashboard\Repositories\Caches;

use Tec\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Tec\Support\Repositories\Caches\CacheAbstractDecorator;

class DashboardWidgetSettingCacheDecorator extends CacheAbstractDecorator implements DashboardWidgetSettingInterface
{
    /**
     * {@inheritDoc}
     */
    public function getListWidget()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
