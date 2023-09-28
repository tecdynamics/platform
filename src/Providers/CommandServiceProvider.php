<?php

namespace Tec\Base\Providers;

use Tec\Base\Commands\ClearLogCommand;
use Tec\Base\Commands\InstallCommand;
use Tec\Base\Commands\PublishAssetsCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            ClearLogCommand::class,
            InstallCommand::class,
            PublishAssetsCommand::class,
        ]);
    }
}
