<?php

namespace Tec\Base\Providers;

use Tec\Base\Supports\BreadcrumbsManager;
use Tec\Base\Supports\ServiceProvider;

/**
 * @deprecated This service provider does not need anymore.
 */
class BreadcrumbsServiceProvider extends ServiceProvider
{
    protected bool $defer = true;

    public function register(): void
    {
        $this->app->singleton(BreadcrumbsManager::class);
    }

    public function provides(): array
    {
        return [BreadcrumbsManager::class];
    }
}
