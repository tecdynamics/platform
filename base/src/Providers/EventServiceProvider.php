<?php

namespace Tec\Base\Providers;

use Tec\Base\Events\BeforeEditContentEvent;
use Tec\Base\Events\CreatedContentEvent;
use Tec\Base\Events\DeletedContentEvent;
use Tec\Base\Events\SendMailEvent;
use Tec\Base\Events\UpdatedContentEvent;
use Tec\Base\Listeners\BeforeEditContentListener;
use Tec\Base\Listeners\CreatedContentListener;
use Tec\Base\Listeners\DeletedContentListener;
use Tec\Base\Listeners\SendMailListener;
use Tec\Base\Listeners\UpdatedContentListener;
use Illuminate\Support\Facades\Event;
use File;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SendMailEvent::class          => [
            SendMailListener::class,
        ],
        CreatedContentEvent::class    => [
            CreatedContentListener::class,
        ],
        UpdatedContentEvent::class    => [
            UpdatedContentListener::class,
        ],
        DeletedContentEvent::class    => [
            DeletedContentListener::class,
        ],
        BeforeEditContentEvent::class => [
            BeforeEditContentListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        Event::listen(['cache:cleared'], function () {
            File::delete([storage_path('cache_keys.json'), storage_path('settings.json')]);
        });
    }
}
