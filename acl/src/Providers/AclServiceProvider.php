<?php

namespace Tec\ACL\Providers;

use Tec\ACL\Http\Middleware\Authenticate;
use Tec\ACL\Http\Middleware\RedirectIfAuthenticated;
use Tec\ACL\Models\Activation;
use Tec\ACL\Models\Role;
use Tec\ACL\Models\User;
use Tec\ACL\Repositories\Eloquent\ActivationRepository;
use Tec\ACL\Repositories\Eloquent\RoleRepository;
use Tec\ACL\Repositories\Eloquent\UserRepository;
use Tec\ACL\Repositories\Interfaces\ActivationInterface;
use Tec\ACL\Repositories\Interfaces\RoleInterface;
use Tec\ACL\Repositories\Interfaces\UserInterface;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Facades\DashboardMenu;
use Tec\Base\Facades\EmailHandler;
use Tec\Base\Supports\ServiceProvider;
use Tec\Base\Traits\LoadAndPublishDataTrait;
use Tec\Media\Facades\RvMedia;
use Exception;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as IlluminateView;

class AclServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(UserInterface::class, function () {
            return new UserRepository(new User());
        });

        $this->app->bind(ActivationInterface::class, function () {
            return new ActivationRepository(new Activation());
        });

        $this->app->bind(RoleInterface::class, function () {
            return new RoleRepository(new Role());
        });
    }

    public function boot(): void
    {
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->setNamespace('core/acl')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general', 'permissions', 'email'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadRoutes()
            ->loadMigrations();

        $this->garbageCollect();

        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-core-role-permission',
                    'priority' => 2,
                    'parent_id' => 'cms-core-platform-administration',
                    'name' => 'core/acl::permissions.role_permission',
                    'icon' => null,
                    'url' => route('roles.index'),
                    'permissions' => ['roles.index'],
                ])
                ->registerItem([
                    'id' => 'cms-core-user',
                    'priority' => 3,
                    'parent_id' => 'cms-core-platform-administration',
                    'name' => 'core/acl::users.users',
                    'icon' => null,
                    'url' => route('users.index'),
                    'permissions' => ['users.index'],
                ]);

            $router = $this->app['router'];

            $router->aliasMiddleware('auth', Authenticate::class);
            $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);
        });

        $this->app->booted(function () {
            config()->set(['auth.providers.users.model' => User::class]);

            EmailHandler::addTemplateSettings('acl', config('core.acl.email', []), 'core');

            $this->app->register(HookServiceProvider::class);

            View::composer('core/acl::auth.master', function (IlluminateView $view) {
                $view->with('backgroundUrl', $this->getLoginPageBackgroundUrl());
            });
        });
    }

    protected function getLoginPageBackgroundUrl(): string
    {
        $default = url(Arr::random(config('core.acl.general.backgrounds', [])));

        $images = setting('login_screen_backgrounds', []);

        if (! $images) {
            return $default;
        }

        $images = is_array($images) ? $images : json_decode($images, true);

        $images = array_filter($images);

        if (empty($images)) {
            return $default;
        }

        $image = Arr::random($images);

        if (! $image) {
            return $default;
        }

        return RvMedia::getImageUrl($image);
    }

    /**
     * Garbage collect activations and reminders.
     */
    protected function garbageCollect(): void
    {
        $config = $this->app->make('config')->get('core.acl.general');

        $this->sweep($this->app->make(ActivationInterface::class), Arr::get($config, 'activations.lottery', [2, 100]));
    }

    protected function sweep(ActivationInterface $repository, array $lottery): void
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (Exception $exception) {
                BaseHelper::logError($exception);
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     */
    protected function configHitsLottery(array $lottery): bool
    {
        return mt_rand(1, $lottery[1]) <= $lottery[0];
    }
}
