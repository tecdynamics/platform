<?php

namespace Tec\Base\Traits;

use Tec\Base\Http\Responses\BaseHttpResponse;

trait HasHttpResponse
{
    public function httpResponse(): BaseHttpResponse
    {
        return BaseHttpResponse::make();
    }
}
