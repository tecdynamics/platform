<?php

namespace Tec\Base\Events;

use Tec\Base\Supports\AdminNotificationItem;
use Illuminate\Queue\SerializesModels;

class AdminNotificationEvent extends Event
{
    use SerializesModels;

    public function __construct(public AdminNotificationItem $item)
    {
    }
}
