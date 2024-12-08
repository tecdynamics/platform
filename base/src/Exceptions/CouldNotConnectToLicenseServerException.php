<?php

namespace Tec\Base\Exceptions;

use Tec\Base\Contracts\Exceptions\IgnoringReport;
use Illuminate\Http\Client\ConnectionException;

class CouldNotConnectToLicenseServerException extends ConnectionException implements IgnoringReport
{
}
