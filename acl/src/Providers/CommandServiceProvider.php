<?php

namespace Tec\ACL\Providers;

use Tec\ACL\Commands\UserCreateCommand;
use Tec\ACL\Commands\UserPasswordCommand;
use Tec\Base\Supports\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            UserCreateCommand::class,
            UserPasswordCommand::class,
        ]);
    }
}
