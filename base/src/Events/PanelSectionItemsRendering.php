<?php

namespace Tec\Base\Events;

use Tec\Base\Contracts\PanelSections\PanelSection;
use Illuminate\Foundation\Events\Dispatchable;

class PanelSectionItemsRendering
{
    use Dispatchable;

    public function __construct(public PanelSection $section, public array $items)
    {
    }
}
