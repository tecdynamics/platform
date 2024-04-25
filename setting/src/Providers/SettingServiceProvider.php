<?php

namespace Tec\Setting\Providers;

use Tec\Base\Events\PanelSectionsRendering;
use Tec\Base\Facades\DashboardMenu;
use Tec\Base\Facades\EmailHandler;
use Tec\Base\Facades\PanelSectionManager;
use Tec\Base\PanelSections\PanelSectionItem;
use Tec\Base\PanelSections\System\SystemPanelSection;
use Tec\Base\Supports\ServiceProvider;
use Tec\Base\Traits\LoadAndPublishDataTrait;
use Tec\Setting\Commands\CronJobTestCommand;
use Tec\Setting\Facades\Setting;
use Tec\Setting\Listeners\PushDashboardMenuToOtherSectionPanel;
use Tec\Setting\Models\Setting as SettingModel;
use Tec\Setting\PanelSections\SettingCommonPanelSection;
use Tec\Setting\PanelSections\SettingOthersPanelSection;
use Tec\Setting\Repositories\Eloquent\SettingRepository;
use Tec\Setting\Repositories\Interfaces\SettingInterface;
use Tec\Setting\Supports\DatabaseSettingStore;
use Tec\Setting\Supports\SettingStore;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;

class SettingServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $defer = true;

    public function register(): void
    {
        $this->setNamespace('core/setting')
            ->loadAndPublishConfigurations(['general']);

        $this->app->singleton(SettingStore::class, function () {
            return new DatabaseSettingStore();
        });

        $this->app->bind(SettingInterface::class, function () {
            return new SettingRepository(new SettingModel());
        });

        if (! class_exists('Setting')) {
            AliasLoader::getInstance()->alias('Setting', Setting::class);
        }

        $this->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAnonymousComponents()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions', 'email'])
            ->loadMigrations()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-core-settings',
                    'priority' => 9999,
                    'parent_id' => null,
                    'name' => 'core/setting::setting.title',
                    'icon' => 'fa fa-cogs',
										 'url' => route('settings.index'),
                    'permissions' => ['settings.options'],
                ]);
//                ->registerItem([
//                    'id' => 'cms-core-settings-general',
//                    'priority' => 1,
//                    'parent_id' => 'cms-core-settings',
//                    'name' => 'core/base::layouts.setting_general',
//                    'icon' => null,
//                    'url' => route('settings.options'),
//                    'permissions' => ['settings.options'],
//                ])
//                ->registerItem([
//                    'id' => 'cms-core-settings-email',
//                    'priority' => 2,
//                    'parent_id' => 'cms-core-settings',
//                    'name' => 'core/base::layouts.setting_email',
//                    'icon' => null,
//                    'url' => route('settings.email'),
//                    'permissions' => ['settings.email'],
//                ])
//                ->registerItem([
//                    'id' => 'cms-core-settings-media',
//                    'priority' => 3,
//                    'parent_id' => 'cms-core-settings',
//                    'name' => 'core/setting::setting.media.title',
//                    'icon' => null,
//                    'url' => route('settings.media'),
//                    'permissions' => ['settings.media'],
//                ])
//                ->registerItem([
//                    'id' => 'cms-core-settings-cronjob',
//                    'priority' => 999,
//                    'parent_id' => 'cms-core-settings',
//                    'name' => 'core/setting::setting.cronjob.name',
//                    'url' => route('settings.cronjob'),
//                    'permissions' => ['settings.cronjob'],
//                ]);
					 PanelSectionManager::default()
							->beforeRendering(function () {
								 PanelSectionManager::setGroupName(trans('core/setting::setting.title'))
										->register([
																	SettingCommonPanelSection::class,
																	SettingOthersPanelSection::class,
															 ]);
							});

					 PanelSectionManager::group('system')->beforeRendering(function () {
							PanelSectionManager::registerItem(
								 SystemPanelSection::class,
								 fn () => PanelSectionItem::make('cronjob')
										->setTitle(trans('core/setting::setting.cronjob.name'))
										->withIcon('ti ti-calendar-event')
										->withDescription(trans('core/setting::setting.cronjob.description'))
										->withPriority(50)
										->withRoute('system.cronjob')
							);
					 });
					 $events = $this->app['events'];
					 $events->listen(PanelSectionsRendering::class, PushDashboardMenuToOtherSectionPanel::class);
            EmailHandler::addTemplateSettings('base', config('core.setting.email', []), 'core');
        });
			 if ($this->app->runningInConsole()) {
        $this->commands([
            CronJobTestCommand::class,
        ]);

        $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
            rescue(function () use ($schedule) {
                $schedule
                    ->command(CronJobTestCommand::class)
                    ->everyMinute();
            });
        });
      }
    }

    public function provides(): array
    {
        return [
            SettingStore::class,
        ];
    }
}
