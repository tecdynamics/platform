<?php

namespace Tec\ACL\Providers;

use Tec\ACL\Commands\UserCreateCommand;
use Tec\Base\Supports\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UserCreateCommand::class,
            ]);
        }
    }
}
