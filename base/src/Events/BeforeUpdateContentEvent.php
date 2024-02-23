<?php

namespace Tec\Base\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Tec\Base\Contracts\BaseModel;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class BeforeUpdateContentEvent extends Event
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Request $request, public bool|BaseModel|null $data)
    {
    }
}
