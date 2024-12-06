<?php

namespace Tec\ACL\Events;

use Tec\ACL\Models\Role;
use Tec\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleUpdateEvent extends Event
{
    use SerializesModels;

    public function __construct(public Role $role)
    {
    }
}
