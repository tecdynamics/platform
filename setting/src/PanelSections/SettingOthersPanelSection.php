<?php

namespace Tec\Setting\PanelSections;

use Tec\Base\PanelSections\PanelSection;

class SettingOthersPanelSection extends PanelSection
{
    public function setup(): void
    {
        $this
            ->setId('settings.others')
            ->setTitle(trans('core/setting::setting.panel.others'))
            ->withPriority(99998);
    }
}
