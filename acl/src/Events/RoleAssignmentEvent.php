<?php

namespace Tec\ACL\Events;

use Tec\ACL\Models\Role;
use Tec\ACL\Models\User;
use Tec\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleAssignmentEvent extends Event
{
    use SerializesModels;

    public function __construct(public Role $role, public User $user)
    {
    }
}
