<?php

namespace Tec\Base\Providers;

use App\Http\Middleware\VerifyCsrfToken;
use Tec\Base\Exceptions\Handler;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Facades\DashboardMenu;
use Tec\Base\Facades\MetaBox;
use Tec\Base\Facades\PageTitle;
use Tec\Base\Forms\Form;
use Tec\Base\Forms\FormBuilder;
use Tec\Base\Forms\FormHelper;

use Tec\Base\Hooks\EmailSettingHooks;
use Tec\Base\Http\Middleware\CoreMiddleware;
use Tec\Base\Http\Middleware\DisableInDemoModeMiddleware;
use Tec\Base\Http\Middleware\HttpsProtocolMiddleware;
use Tec\Base\Http\Middleware\LocaleMiddleware;
use Tec\Base\Models\AdminNotification;
use Tec\Base\Models\BaseModel;
use Tec\Base\Models\MetaBox as MetaBoxModel;
use Tec\Base\Repositories\Eloquent\MetaBoxRepository;
use Tec\Base\Repositories\Interfaces\MetaBoxInterface;
use Tec\Base\Supports\Action;
use Tec\Base\Supports\BreadcrumbsManager;
use Tec\Base\Supports\CustomResourceRegistrar;
use Tec\Base\Supports\DashboardMenu as DashboardMenuSupport;
use Tec\Base\Supports\Database\Blueprint;
use Tec\Base\Supports\Filter;
use Tec\Base\Supports\GoogleFonts;
use Tec\Base\Supports\Helper;
use Tec\Base\Supports\ServiceProvider;
use Tec\Base\Traits\LoadAndPublishDataTrait;
use Tec\Base\Widgets\AdminWidget;
use Tec\Base\Widgets\Contracts\AdminWidget as AdminWidgetContract;
use Tec\Setting\Providers\SettingServiceProvider;
use Tec\Setting\Supports\SettingStore;
use Tec\Support\Http\Middleware\BaseMiddleware;
use DateTimeZone;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Schema\Builder;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Throwable;

class BaseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $defer = true;

    public function register(): void
    {
        $this->app->bind(ResourceRegistrar::class, function ($app) {
            return new CustomResourceRegistrar($app['router']);
        });

        $this->setNamespace('core/base')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general']);

        $this->app->register(SettingServiceProvider::class);

        $this->app->singleton(ExceptionHandler::class, Handler::class);

        $this->app->singleton(BreadcrumbsManager::class, BreadcrumbsManager::class);

        $this->app->bind(MetaBoxInterface::class, function () {
            return new MetaBoxRepository(new MetaBoxModel());
        });

        $this->app['config']->set([
            'session.cookie' => 'Tec_session',
            'ziggy.except' => ['debugbar.*'],
            'app.debug_blacklist' => [
                '_ENV' => [
                    'APP_KEY',
                    'ADMIN_DIR',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'REDIS_PASSWORD',
                    'MAIL_PASSWORD',
                    'PUSHER_APP_KEY',
                    'PUSHER_APP_SECRET',
                ],
                '_SERVER' => [
                    'APP_KEY',
                    'ADMIN_DIR',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'REDIS_PASSWORD',
                    'MAIL_PASSWORD',
                    'PUSHER_APP_KEY',
                    'PUSHER_APP_SECRET',
                ],
                '_POST' => [
                    'password',
                ],
            ],
            'datatables-buttons.pdf_generator' => 'excel',
            'excel.exports.csv.use_bom' => true,
            'dompdf.public_path' => public_path(),
            'debugbar.enabled' => $this->app->hasDebugModeEnabled() &&
                ! $this->app->runningInConsole() &&
                ! $this->app->environment(['testing', 'production']),
            'laravel-form-builder.plain_form_class' => Form::class,
            'laravel-form-builder.form_builder_class' => FormBuilder::class,
            'laravel-form-builder.form_helper_class' => FormHelper::class,
            'laravel-form-builder.label_class' => 'control-label',
            'laravel-form-builder.wrapper_class' => 'form-group',
        ]);

        $this->app->singleton('core:action', function () {
            return new Action();
        });

        $this->app->singleton('core:filter', function () {
            return new Filter();
        });

        $this->app->singleton(AdminWidgetContract::class, AdminWidget::class);

        $this->app->singleton('core.google-fonts', function (Application $app) {
            return new GoogleFonts(
                filesystem: $app->make(FilesystemManager::class)->disk('public'),
                path: 'fonts',
                inline: true,
                userAgent: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15',
            );
        });

        Route::macro('wherePrimaryKey', function (array|string|null $name = 'id') {
            if (! $name) {
                $name = 'id';
            }

            /**
             * @var Route $this
             */
            if (BaseModel::determineIfUsingUuidsForId()) {
                return $this->whereUuid($name);
            }

            if (BaseModel::determineIfUsingUlidsForId()) {
                return $this->whereUlid($name);
            }

            return $this->whereNumber($name);
        });

        $aliasLoader = AliasLoader::getInstance();

        if (! class_exists('BaseHelper')) {
            $aliasLoader->alias('BaseHelper', BaseHelper::class);
            $aliasLoader->alias('DashboardMenu', DashboardMenu::class);
            $aliasLoader->alias('PageTitle', PageTitle::class);
            $aliasLoader->alias('Form', \Tec\Base\Facades\Form::class);
        }
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['permissions', 'assets'])
            ->loadAndPublishViews()
            ->loadAnonymousComponents()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        $this->app['blade.compiler']->anonymousComponentPath($this->getViewsPath() . '/components', 'core');

        Schema::defaultStringLength(191);

        $config = $this->app['config'];

        if (BaseHelper::hasDemoModeEnabled() || $config->get('core.base.general.disable_verify_csrf_token', false)) {
            $this->app->instance(VerifyCsrfToken::class, new BaseMiddleware());
        }

        $this->app->booted(function () use ($config) {
            do_action(BASE_ACTION_INIT);
            add_action(BASE_ACTION_META_BOXES, [MetaBox::class, 'doMetaBoxes'], 8, 2);
            add_filter(
                BASE_FILTER_AFTER_SETTING_EMAIL_CONTENT,
                [EmailSettingHooks::class, 'addEmailTemplateSettings'],
                99
            );

            add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, function ($options) {
                try {
                    $countNotificationUnread = AdminNotification::countUnread();
                } catch (Throwable) {
                    $countNotificationUnread = 0;
                }

                return $options . view('core/base::notification.notification', compact('countNotificationUnread'));
            }, 99);

            add_filter(BASE_FILTER_FOOTER_LAYOUT_TEMPLATE, function ($html) {
                if (! Auth::guard()->check()) {
                    return $html;
                }

                return $html . view('core/base::notification.notification-content');
            }, 99);

            $setting = $this->app[SettingStore::class];
            $timezone = $setting->get('time_zone', $config->get('app.timezone'));
            $locale = $setting->get('locale', $config->get('core.base.general.locale', $config->get('app.locale')));

            $config->set([
                'app.locale' => $locale,
                'app.timezone' => $timezone,
            ]);

            $this->app->setLocale($locale);

            if (in_array($timezone, DateTimeZone::listIdentifiers())) {
                date_default_timezone_set($timezone);
            }
        });

        $this->app['events']->listen(RouteMatched::class, function () {
            $this->registerDefaultMenus();

            /**
             * @var Router $router
             */
            $router = $this->app['router'];

            $router->pushMiddlewareToGroup('web', LocaleMiddleware::class);
            $router->pushMiddlewareToGroup('web', HttpsProtocolMiddleware::class);
            $router->aliasMiddleware('preventDemo', DisableInDemoModeMiddleware::class);
            $router->middlewareGroup('core', [CoreMiddleware::class]);
        });

        Paginator::useBootstrap();

        $baseConfig = $config->get('core.base.general', []);

        $forceUrl = Arr::get($baseConfig, 'force_root_url');
        if (! empty($forceUrl)) {
            URL::forceRootUrl($forceUrl);
        }

        $forceSchema = Arr::get($baseConfig, 'force_schema');
        if (! empty($forceSchema)) {
            $this->app['request']->server->set('HTTPS', 'on');

            URL::forceScheme($forceSchema);
        }

        $this->configureIni();

        $config->set([
            'purifier.settings' => array_merge(
                $config->get('purifier.settings', []),
                Arr::get($baseConfig, 'purifier', [])
            ),
            'laravel-form-builder.defaults.wrapper_class' => 'form-group mb-3',
            'database.connections.mysql.strict' => Arr::get($baseConfig, 'db_strict_mode'),
        ]);

        if (
            ! $config->has('logging.channels.deprecations')
            && $this->app->isLocal()
            && $this->app->hasDebugModeEnabled()
        ) {
            $config->set([
                'logging.channels.deprecations' => [
                    'driver' => 'single',
                    'path' => storage_path('logs/php-deprecation-warnings.log'),
                ],
            ]);
        }

        if ($this->app->runningInConsole()) {
            AboutCommand::add('Core Information', fn () => [
                'CMS Version' => get_cms_version(),
                'Core Version' => get_core_version(),
            ]);
        }

        $this->app->extend('db.schema', function (Builder $schema) {
            $schema->blueprintResolver(function ($table, $callback, $prefix) {
                return new Blueprint($table, $callback, $prefix);
            });

            return $schema;
        });

        if ($this->app->environment('local')) {
            DB::listen(function (QueryExecuted $queryExecuted) {
                if ($queryExecuted->time < 500) {
                    return;
                }

                Log::warning(sprintf('DB query exceeded %s ms. SQL: %s', $queryExecuted->time, $queryExecuted->sql));
            });
        }
    }

    /**
     * Add default dashboard menu for core
     */
    public function registerDefaultMenus(): void
    {
        $config = $this->app['config'];

        DashboardMenu::make()
            ->registerItem([
                'id' => 'cms-core-platform-administration',
                'priority' => 999,
                'parent_id' => null,
                'name' => 'core/base::layouts.platform_admin',
                'icon' => 'fa fa-user-shield',
                'url' => null,
                'permissions' => ['users.index'],
            ])
            ->registerItem([
                'id' => 'cms-core-system-information',
                'priority' => 5,
                'parent_id' => 'cms-core-platform-administration',
                'name' => 'core/base::system.info.title',
                'icon' => null,
                'url' => route('system.info'),
                'permissions' => [ACL_ROLE_SUPER_USER],
            ])
            ->registerItem([
                'id' => 'cms-core-system-cache',
                'priority' => 6,
                'parent_id' => 'cms-core-platform-administration',
                'name' => 'core/base::cache.cache_management',
                'icon' => null,
                'url' => route('system.cache'),
                'permissions' => [ACL_ROLE_SUPER_USER],
            ])
            ->when(
                ! $config->get('core.base.general.hide_cleanup_system_menu', false),
                function (DashboardMenuSupport $menu) {
                    $menu->registerItem([
                        'id' => 'cms-core-system-cleanup',
                        'priority' => 999,
                        'parent_id' => 'cms-core-platform-administration',
                        'name' => 'core/base::system.cleanup.title',
                        'icon' => null,
                        'url' => route('system.cleanup'),
                        'permissions' => [ACL_ROLE_SUPER_USER],
                    ]);
                }
            )
            ->when($config->get('core.base.general.enable_system_updater'), function (DashboardMenuSupport $menu) {
                $menu->registerItem([
                    'id' => 'cms-core-system-updater',
                    'priority' => 999,
                    'parent_id' => 'cms-core-platform-administration',
                    'name' => 'core/base::system.updater',
                    'icon' => null,
                    'url' => route('system.updater'),
                    'permissions' => [ACL_ROLE_SUPER_USER],
                ]);
            });
    }

    protected function configureIni(): void
    {
        $currentLimit = @ini_get('memory_limit');
        $currentLimitInt = Helper::convertHrToBytes($currentLimit);

        $memoryLimit = $this->app['config']->get('core.base.general.memory_limit');

        if (! $memoryLimit) {
            if (false === Helper::isIniValueChangeable('memory_limit')) {
                $memoryLimit = $currentLimit;
            } else {
                $memoryLimit = '128M';
            }
        }

        $limitInt = Helper::convertHrToBytes($memoryLimit);
        if (-1 !== $currentLimitInt && (-1 === $limitInt || $limitInt > $currentLimitInt)) {
            BaseHelper::iniSet('memory_limit', $memoryLimit);
        }
    }

    public function provides(): array
    {
        return [BreadcrumbsManager::class];
    }
}
