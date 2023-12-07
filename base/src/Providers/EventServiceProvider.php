<?php

namespace Tec\Base\Providers;

use Tec\Base\Events\AdminNotificationEvent;
use Tec\Base\Events\BeforeEditContentEvent;
use Tec\Base\Events\CreatedContentEvent;
use Tec\Base\Events\DeletedContentEvent;
use Tec\Base\Events\SendMailEvent;
use Tec\Base\Events\UpdatedContentEvent;
use Tec\Base\Listeners\AdminNotificationListener;
use Tec\Base\Listeners\BeforeEditContentListener;
use Tec\Base\Listeners\CreatedContentListener;
use Tec\Base\Listeners\DeletedContentListener;
use Tec\Base\Listeners\SendMailListener;
use Tec\Base\Listeners\UpdatedContentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SendMailEvent::class => [
            SendMailListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
        BeforeEditContentEvent::class => [
            BeforeEditContentListener::class,
        ],
        AdminNotificationEvent::class => [
            AdminNotificationListener::class,
        ],
    ];

    public function boot(): void
    {
        $this->app['events']->listen(['cache:cleared'], function () {
            $this->app['files']->delete(storage_path('cache_keys.json'));
        });
    }
}
