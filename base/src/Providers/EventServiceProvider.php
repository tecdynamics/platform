<?php

namespace Tec\Base\Providers;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
use Tec\Base\Facades\MetaBox;
use Tec\Base\Http\Middleware\AdminLocaleMiddleware;
use Tec\Base\Http\Middleware\CoreMiddleware;
use Tec\Base\Http\Middleware\DisableInDemoModeMiddleware;
use Tec\Base\Http\Middleware\EnsureLicenseHasBeenActivated;
use Tec\Base\Http\Middleware\HttpsProtocolMiddleware;
use Tec\Base\Http\Middleware\LocaleMiddleware;
use Tec\Base\Listeners\ClearDashboardMenuCachesForLoggedUser;
use Tec\Base\Models\AdminNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tec\ACL\Events\RoleAssignmentEvent;
use Tec\ACL\Events\RoleUpdateEvent;
use Tec\Base\Events\AdminNotificationEvent;
use Tec\Base\Events\BeforeEditContentEvent;
use Tec\Base\Events\CreatedContentEvent;
use Tec\Base\Events\DeletedContentEvent;
use Tec\Base\Events\SendMailEvent;
use Tec\Base\Events\UpdatedContentEvent;
use Tec\Base\Events\UpdatedEvent;
use Tec\Base\Facades\AdminHelper;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Listeners\AdminNotificationListener;
use Tec\Base\Listeners\BeforeEditContentListener;
use Tec\Base\Listeners\ClearDashboardMenuCaches;
use Tec\Base\Listeners\CreatedContentListener;
use Tec\Base\Listeners\DeletedContentListener;
use Tec\Base\Listeners\SendMailListener;
use Tec\Base\Listeners\UpdatedContentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Tec\Support\Http\Middleware\BaseMiddleware;

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
        UpdatedEvent::class => [
            ClearDashboardMenuCaches::class,
        ],
        Login::class => [
            ClearDashboardMenuCachesForLoggedUser::class,
        ],
        RoleAssignmentEvent::class => [
            ClearDashboardMenuCaches::class,
        ],
        RoleUpdateEvent::class => [
            ClearDashboardMenuCaches::class,
        ],
    ];

    public function boot(): void
    {
        $this->app['events']->listen(['cache:cleared'], function () {
            $this->app['files']->delete(storage_path('cache_keys.json'));
        });
        $events = $this->app['events'];
        $events->listen(RouteMatched::class, function () {
            /**
             * @var Router $router
             */
            $router = $this->app['router'];

            $router->pushMiddlewareToGroup('web', LocaleMiddleware::class);
            $router->pushMiddlewareToGroup('web', AdminLocaleMiddleware::class);
            $router->pushMiddlewareToGroup('web', HttpsProtocolMiddleware::class);
            $router->aliasMiddleware('preventDemo', DisableInDemoModeMiddleware::class);
            $router->middlewareGroup('core', [CoreMiddleware::class]);

            $this->app->extend('core.middleware', function ($middleware) {
                return array_merge($middleware, [
                    EnsureLicenseHasBeenActivated::class,
                ]);
            });

            add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, function ($options) {
                try {
                    $countNotificationUnread = AdminNotification::countUnread();
                } catch (Throwable) {
                    $countNotificationUnread = 0;
                }

                return $options . view('core/base::notification.nav-item', compact('countNotificationUnread'));
            }, 99);

            add_filter(BASE_FILTER_FOOTER_LAYOUT_TEMPLATE, function ($html) {
                if (! Auth::guard()->check()) {
                    return $html;
                }

                return $html . view('core/base::notification.notification');
            }, 99);

            add_action(BASE_ACTION_META_BOXES, [MetaBox::class, 'doMetaBoxes'], 8, 2);

            $this->disableCsrfProtection();
        });

        $events->listen(MigrationsStarted::class, function () {
            rescue(function () {
                if (DB::getDefaultConnection() === 'mysql') {
                    DB::statement('SET SESSION sql_require_primary_key=0');
                }
            }, report: false);
        });
        if ($this->app->isLocal()) {
            DB::listen(function (QueryExecuted $queryExecuted) {
                if ($queryExecuted->time < 500) {
                    return;
                }

                Log::warning(sprintf('DB query exceeded %s ms. SQL: %s', $queryExecuted->time, $queryExecuted->sql));
            });
        }
    }
    protected function disableCsrfProtection(): void
    {
        /**
         * @var Repository $config
         */
        $config = $this->app['config'];

        if (
            BaseHelper::hasDemoModeEnabled()
            || $config->get('core.base.general.disable_verify_csrf_token', false)
            || ($this->app->environment('production') && AdminHelper::isInAdmin())
        ) {
            $this->app->instance(VerifyCsrfToken::class, new BaseMiddleware());
        }
    }
}
