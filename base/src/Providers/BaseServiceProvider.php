<?php

namespace Tec\Base\Providers;

use Tec\Base\GlobalSearch\GlobalSearchableManager;
use Tec\Base\Contracts\PanelSections\Manager;
use Tec\Base\PanelSections\System\SystemPanelSection;
use Tec\Base\Facades\AdminAppearance;
use Tec\Base\Facades\AdminHelper;
use Tec\Base\Facades\Breadcrumb as BreadcrumbFacade;
use Tec\Base\PanelSections\Manager as PanelSectionManager;
use Tec\Base\Facades\PanelSectionManager as PanelSectionManagerFacade;
use Illuminate\Config\Repository;
use Tec\Base\Exceptions\Handler;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Facades\DashboardMenu;
use Tec\Base\Supports\Breadcrumb;
use Tec\Base\Contracts\GlobalSearchableManager as GlobalSearchableManagerContract;
use Tec\Base\Models\BaseModel;
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
use DateTimeZone;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Schema\Builder;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class BaseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $defer = true;

    public function register(): void
    {
        $this->app->bind(ResourceRegistrar::class, function ($app) {
            return new CustomResourceRegistrar($app['router']);
        });

        $this
            ->setNamespace('core/base')
            ->loadAndPublishConfigurations('general')
            ->loadHelpers();

        $this->app->instance('core.middleware', []);

        $this->app->bind(ResourceRegistrar::class, function (Application $app) {
            return new CustomResourceRegistrar($app['router']);
        });
        $this->app->register(SettingServiceProvider::class);

        $this->app->singleton(ExceptionHandler::class, Handler::class);
        $this->app->register(SettingServiceProvider::class);

        $this->app->singleton(ExceptionHandler::class, Handler::class);

        $this->app->singleton(Breadcrumb::class);
        $this->app->singleton(BreadcrumbsManager::class, BreadcrumbsManager::class);
        $this->app->singleton(Manager::class, PanelSectionManager::class);
        $this->app->singleton(GlobalSearchableManagerContract::class, GlobalSearchableManager::class);
//        $this->app->bind(MetaBoxInterface::class, function () {
//            return new MetaBoxRepository(new MetaBoxModel());
//        });
        $this->app->bind(MetaBoxInterface::class, MetaBoxRepository::class);
//        $this->app->singleton('core:action', function () {
//            return new Action();
//        });
//
//        $this->app->singleton('core:filter', function () {
//            return new Filter();
//        });
        $this->app->singleton('core.action', Action::class);

        $this->app->singleton('core.filter', Filter::class);
        $this->app->singleton(AdminWidgetContract::class, AdminWidget::class);


        $this->app->singleton('core.google-fonts', GoogleFonts::class);

        $this->registerRouteMacros();

        $this->prepareAliasesIfMissing();

        config()->set(['session.cookie' => 'tec_session']);

        $this->overrideDefaultConfigs();
        Schema::defaultStringLength(191);
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

        $this->overridePackagesConfigs();

        $this->app->booted(function () {
            do_action(BASE_ACTION_INIT);
        });
        $this->registerDashboardMenus();

        $this->registerPanelSections();

        Paginator::useBootstrap();

        $this->forceSSL();

        $this->configureIni();

        $this->app->extend('db.schema', function (Builder $schema) {
            $schema->blueprintResolver(function ($table, $callback, $prefix) {
                return new Blueprint($table, $callback, $prefix);
            });

            return $schema;
        });
    }

    protected function registerDashboardMenus(): void
    {
        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-core-system',
                    'priority' => 10000,
                    'name' => 'core/base::layouts.platform_admin',
                    'icon' => 'ti ti-user-shield',
                    'route' => 'system.index',
                    'permissions' => ['core.system'],
                ]);
        });
    }
    protected function registerPanelSections(): void
    {
        \Tec\Base\Facades\PanelSectionManager::group('system')->beforeRendering(function () {
            PanelSectionManagerFacade::setGroupName(trans('core/base::layouts.platform_admin'))
                ->register(SystemPanelSection::class);
        });
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
        return [Breadcrumb::class];
    }

    protected function forceSSL(): void
    {
        $baseConfig = $this->getBaseConfig();

        $forceUrl = Arr::get($baseConfig, 'force_root_url');
        if (! empty($forceUrl)) {
            URL::forceRootUrl($forceUrl);
        }

        $forceSchema = Arr::get($baseConfig, 'force_schema');
        if (! empty($forceSchema)) {
            $this->app['request']->server->set('HTTPS', 'on');

            URL::forceScheme($forceSchema);
        }
    }

    protected function getBaseConfig(): array
    {
        return $this->getConfig()->get('core.base.general', []);
    }

    protected function getConfig(): Repository
    {
        return $this->app['config'];
    }

    protected function overrideDefaultConfigs(): void
    {
        $config = $this->getConfig();

        $config->set([
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
            'debugbar.enabled' => $this->app->hasDebugModeEnabled() &&
                ! $this->app->runningInConsole() &&
                ! $this->app->environment(['testing', 'production']),
            'debugbar.capture_ajax' => false,
            'debugbar.remote_sites_path' => '',
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
    }

    protected function overridePackagesConfigs(): void
    {
        $config = $this->getConfig();

        $baseConfig = $this->getBaseConfig();

        /**
         * @var \Tec\Setting\Supports\SettingStore $setting
         */
        $setting = $this->app[SettingStore::class];
        $timezone = $setting->get('time_zone', $config->get('app.timezone'));
        $locale = $setting->get('locale', Arr::get($baseConfig, 'locale', $config->get('app.locale')));

        $this->app->setLocale($locale);

        if (in_array($timezone, DateTimeZone::listIdentifiers())) {
            date_default_timezone_set($timezone);
        }

        $config->set([
            'app.locale' => $locale,
            'app.timezone' => $timezone,
            'purifier.settings' => [
                ...$config->get('purifier.settings', []),
                ...Arr::get($baseConfig, 'purifier', []),
            ],
            'ziggy.except' => ['debugbar.*'],
            'datatables-buttons.pdf_generator' => 'excel',
            'excel.exports.csv.use_bom' => true,
            'dompdf.public_path' => public_path(),
            'database.connections.mysql.strict' => Arr::get($baseConfig, 'db_strict_mode'),
            'excel.imports.ignore_empty' => true,
            'excel.imports.csv.input_encoding' => Arr::get($baseConfig, 'csv_import_input_encoding', 'UTF-8'),
        ]);
    }

    protected function registerRouteMacros(): void
    {
        Route::macro('wherePrimaryKey', function (array|string|null $name = 'id') {
            if (! $name) {
                $name = 'id';
            }

            /**
             * @var Route $this
             */
            if (\Tec\Base\Models\BaseModel::determineIfUsingUuidsForId()) {
                return $this->whereUuid($name);
            }

            if (BaseModel::determineIfUsingUlidsForId()) {
                return $this->whereUlid($name);
            }

            return $this->whereNumber($name);
        });
    }

    protected function prepareAliasesIfMissing(): void
    {
        $aliasLoader = AliasLoader::getInstance();

        if (! class_exists('BaseHelper')) {
            $aliasLoader->alias('BaseHelper', BaseHelper::class);
            $aliasLoader->alias('DashboardMenu', \Tec\Base\Facades\DashboardMenu::class);
            $aliasLoader->alias('PageTitle', \Tec\Base\Facades\PageTitle::class);
        }

        if (! class_exists('Breadcrumb')) {
            $aliasLoader->alias('Breadcrumb', BreadcrumbFacade::class);
        }

        if (! class_exists('PanelSectionManager')) {
            $aliasLoader->alias('PanelSectionManager', PanelSectionManagerFacade::class);
        }

        if (! class_exists('AdminAppearance')) {
            $aliasLoader->alias('AdminAppearance', AdminAppearance::class);
        }

        if (! class_exists('AdminHelper')) {
            $aliasLoader->alias('AdminHelper', AdminHelper::class);
        }
    }
}
