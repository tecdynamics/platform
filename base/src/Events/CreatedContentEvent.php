<?php

namespace Tec\Base\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class CreatedContentEvent extends Event
{
    use SerializesModels;

    public function __construct(public string $screen, public Request $request, public bool|Model|null $data)
    {
    }
}
