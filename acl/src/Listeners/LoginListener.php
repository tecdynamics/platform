<?php

namespace Tec\ACL\Listeners;

use Tec\ACL\Models\User;
use Illuminate\Auth\Events\Login;

class LoginListener
{
    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }
    }
}
