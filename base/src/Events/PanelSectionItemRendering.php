<?php

namespace Tec\Base\Events;

use Tec\Base\Contracts\PanelSections\PanelSectionItem;
use Illuminate\Foundation\Events\Dispatchable;

class PanelSectionItemRendering
{
    use Dispatchable;

    public function __construct(public PanelSectionItem $item)
    {
    }
}
