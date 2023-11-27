<?php

namespace Tec\Base\Traits;

use Illuminate\Support\Facades\File;
use Tec\Base\Supports\Helper;
use Exception;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * @mixin ServiceProvider
 */
trait LoadAndPublishDataTrait
{
    /**
     * @var string
     */

    protected string|null $namespace = null;

    /**
     * @var string
     */
    protected $basePath = null;

    /**
     * @param string $namespace
     * @return $this
     */

    protected function setNamespace(string $namespace): self
    {
        $this->namespace = ltrim(rtrim($namespace, '/'), '/');

        $this->app['config']->set(['core.base.general.plugin_namespaces.' . File::basename($this->getPath()) => $namespace]);

        return $this;
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param array|string $fileNames
     * @return $this
     */
    public function loadAndPublishConfigurations($fileNames): self
    {
        if (!is_array($fileNames)) {
            $fileNames = [$fileNames];
        }
        foreach ($fileNames as $fileName) {
            $this->mergeConfigFrom($this->getConfigFilePath($fileName), $this->getDotedNamespace() . '.' . $fileName);
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $this->getConfigFilePath($fileName) => config_path($this->getDashedNamespace() . '/' . $fileName . '.php'),
                ], 'cms-config');
            }
        }

        return $this;
    }
    protected function getConfigFilePath(string $file): string
    {
        return $this->getPath('config/' . $file . '.php');
    }

    protected function getDashedNamespace(): string
    {
        return str_replace('.', '/', $this->namespace);
    }

    protected function getDotedNamespace(): string
    {
        return str_replace('/', '.', $this->namespace);
    }
    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath ?? platform_path();
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setBasePath($path): self
    {
        $this->basePath = $path;

        return $this;
    }
    protected function getAssetsPath(): string
    {
        return $this->getPath('public');
    }

    protected function loadHelpers(): self
    {
        \Tec\Base\Supports\Helper::autoload($this->getPath('/helpers'));

        return $this;
    }
    protected function getViewsPath(): string
    {
        return $this->getPath('/resources/views');
    }

    public function loadAndPublishTranslations(): self
    {
        $this->loadTranslationsFrom($this->getTranslationsPath(), $this->getDashedNamespace());
        $this->publishes(
            [$this->getTranslationsPath() => lang_path('vendor/' . $this->getDashedNamespace())],
            'cms-lang'
        );

        return $this;
    }

    protected function getTranslationsPath(): string
    {
        return $this->getPath('/resources/lang');
    }

    protected function loadMigrations(): self
    {
        $this->loadMigrationsFrom($this->getMigrationsPath());

        return $this;
    }

    protected function getMigrationsPath(): string
    {
        return $this->getPath('/database/migrations');
    }

    protected function publishAssets(string $path = null): self
    {
        if (empty($path)) {
            $path = 'vendor/core/' . $this->getDashedNamespace();
        }

        $this->publishes([$this->getAssetsPath() => public_path($path)], 'cms-public');

        return $this;
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param array|string $fileNames
     * @return $this
     */
       protected function loadRoutes(array|string $fileNames = ['web']): self
    {
        if (! is_array($fileNames)) {
            $fileNames = [$fileNames];
        }

        foreach ($fileNames as $fileName) {
            $this->loadRoutesFrom($this->getRouteFilePath($fileName));
        }

        return $this;
    }

    protected function getRouteFilePath(string $file): string
    {
        return $this->getPath('routes/' . $file . '.php');
    }

    protected function loadAndPublishViews(): self
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->getDashedNamespace());
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [$this->getViewsPath() => resource_path('views/vendor/' . $this->getDashedNamespace())],
                'cms-views'
            );
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function throwInvalidPluginError()
    {
        $reflection = new ReflectionClass($this);

        $from = str_replace('/src/Providers', '', dirname($reflection->getFilename()));
        $from = str_replace(base_path(), '', $from);

        $to = $this->getBasePath() . $this->getDashedNamespace();
        $to = str_replace(base_path(), '', $to);

        if ($from != $to) {
            throw new Exception(sprintf('Plugin folder is invalid. Need to rename folder %s to %s', $from, $to));
        }
    }

    protected function loadAnonymousComponents(): self
    {
        $this->app['blade.compiler']->anonymousComponentPath(
            $this->getViewsPath() . '/components',
            str_replace('/', '-', $this->namespace)
        );

        return $this;
    }
    protected function getPath(string $path = null): string
    {
        $reflection = new ReflectionClass($this);

        $modulePath = str_replace('/src/Providers', '', File::dirname($reflection->getFilename()));

        if (! Str::contains($modulePath, base_path('platform/plugins'))) {
            $modulePath = base_path('platform/' . $this->getDashedNamespace());
        }

        return $modulePath . ($path ? '/' . ltrim($path, '/') : '');
    }








}
