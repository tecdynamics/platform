<?php

namespace Tec\Base\Events;

use Tec\Base\Supports\ValueObjects\CoreProduct;
use Illuminate\Foundation\Events\Dispatchable;

class SystemUpdateAvailable
{
    use Dispatchable;

    public function __construct(public CoreProduct $coreProduct)
    {
    }
}
