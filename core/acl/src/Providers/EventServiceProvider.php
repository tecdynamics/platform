<?php

namespace Tec\ACL\Providers;

use Tec\ACL\Events\RoleAssignmentEvent;
use Tec\ACL\Events\RoleUpdateEvent;
use Tec\ACL\Listeners\LoginListener;
use Tec\ACL\Listeners\RoleAssignmentListener;
use Tec\ACL\Listeners\RoleUpdateListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RoleUpdateEvent::class     => [
            RoleUpdateListener::class,
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
        Login::class               => [
            LoginListener::class,
        ],
    ];
}
