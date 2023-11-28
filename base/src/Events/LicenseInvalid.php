<?php

namespace Tec\Base\Events;

use Illuminate\Foundation\Events\Dispatchable;

class LicenseInvalid
{
    use Dispatchable;

    public function __construct(
        public string $licenseKey,
        public string $licenseName,
    ) {
    }
}
