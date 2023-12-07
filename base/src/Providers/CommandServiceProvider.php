<?php

namespace Tec\Base\Providers;

use Tec\Base\Commands\ActivateLicenseCommand;
use Tec\Base\Commands\CleanupSystemCommand;
use Tec\Base\Commands\ClearExpiredCacheCommand;
use Tec\Base\Commands\ClearLogCommand;
use Tec\Base\Commands\ExportDatabaseCommand;
use Tec\Base\Commands\FetchGoogleFontsCommand;
use Tec\Base\Commands\InstallCommand;
use Tec\Base\Commands\PublishAssetsCommand;
use Tec\Base\Commands\UpdateCommand;
use Tec\Base\Supports\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            ActivateLicenseCommand::class,
            CleanupSystemCommand::class,
            ClearExpiredCacheCommand::class,
            ClearLogCommand::class,
            ExportDatabaseCommand::class,
            FetchGoogleFontsCommand::class,
            InstallCommand::class,
            PublishAssetsCommand::class,
            UpdateCommand::class,
        ]);

        $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(ClearExpiredCacheCommand::class)->everyFiveMinutes();
        });
    }
}
