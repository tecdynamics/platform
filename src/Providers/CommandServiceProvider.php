<?php

namespace Tec\Base\Providers;

use Tec\Base\Commands\ActivateLicenseCommand;
use Tec\Base\Commands\CleanupSystemCommand;
use Tec\Base\Commands\ClearExpiredCacheCommand;
use Tec\Base\Commands\ClearLogCommand;
use Tec\Base\Commands\ExportDatabaseCommand;
use Tec\Base\Commands\FetchGoogleFontsCommand;
use Tec\Base\Commands\GoogleFontsUpdateCommand;
use Tec\Base\Commands\ImportDatabaseCommand;
use Tec\Base\Commands\InstallCommand;
use Tec\Base\Commands\PublishAssetsCommand;
use Tec\Base\Commands\UpdateCommand;
use Tec\Base\Supports\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\AboutCommand;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ActivateLicenseCommand::class,
            CleanupSystemCommand::class,
            ClearExpiredCacheCommand::class,
            ClearLogCommand::class,
            ExportDatabaseCommand::class,
            FetchGoogleFontsCommand::class,
            ImportDatabaseCommand::class,
            InstallCommand::class,
            PublishAssetsCommand::class,
            UpdateCommand::class,
            GoogleFontsUpdateCommand::class,
        ]);

        AboutCommand::add('Core Information', fn () => [
            'CMS Version' => get_cms_version(),
            'Core Version' => get_core_version(),
        ]);

        $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(ClearExpiredCacheCommand::class)->everyFiveMinutes();
        });
    }
}
