<?php

namespace Tec\Base\Supports;

use Illuminate\Cache\CacheManager;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Tec\Support\Services\Cache\Cache;
use Closure;
use Illuminate\Support\Traits\Tappable;
use Tec\Base\Facades\BaseHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use RuntimeException;

class DashboardMenu
{
    use Conditionable;
    use Tappable;

    protected array $links = [];

    protected string $groupId = 'admin';

    protected array $beforeRetrieving = [];

    protected array $afterRetrieved = [];

    protected bool $cacheEnabled;

    protected Cache $cache;
    public function __construct(
        protected Application $app,
        protected Request $request,
        CacheManager $cache
    ) {
        $this->cacheEnabled = (bool) setting('cache_admin_menu_enable', false);
        $this->cache = new \Tec\Support\Services\Cache\Cache($cache, static::class);
    }
    public function make(): self
    {
        return $this;
    }
    public function group(string $id, Closure $callback): static
    {
        $this->for($id);

        $callback($this);

        $this->default();

        return $this;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }
    public function registerItem(array $options): self
    {
        if (! is_in_admin(true)) {
            return $this;
        }

        if (isset($options['children'])) {
            unset($options['children']);
        }

        $defaultOptions = [
            'id' => '',
            'priority' => 99,
            'parent_id' => null,
            'name' => '',
            'icon' => null,
            'url' => '',
            'children' => [],
            'permissions' => [],
            'active' => false,
        ];

        $options = array_merge($defaultOptions, $options);
        $id = $options['id'];

        if (! $id && ! app()->runningInConsole() && app()->isLocal()) {
            $calledClass = isset(debug_backtrace()[1]) ?
                debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
                :
                null;

            throw new RuntimeException('Menu id not specified: ' . $calledClass);
        }

        if (isset($this->links[$id]) && $this->links[$id]['name'] && ! app()->runningInConsole() && app()->isLocal()) {
            $calledClass = isset(debug_backtrace()[1]) ?
                debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
                :
                null;

            throw new RuntimeException('Menu id already exists: ' . $id . ' on class ' . $calledClass);
        }

        if (isset($this->links[$id])) {
            $options['children'] = array_merge($options['children'], $this->links[$id]['children']);
            $options['permissions'] = array_merge($options['permissions'], $this->links[$id]['permissions']);

            $this->links[$id] = array_replace($this->links[$id], $options);

            return $this;
        }

        if ($options['parent_id']) {
            if (! isset($this->links[$options['parent_id']])) {
                $this->links[$options['parent_id']] = ['id' => $options['parent_id']] + $defaultOptions;
            }

            $this->links[$options['parent_id']]['children'][] = $options;

            $permissions = array_merge($this->links[$options['parent_id']]['permissions'], $options['permissions']);
            $this->links[$options['parent_id']]['permissions'] = $permissions;
        } else {
            $this->links[$id] = $options;
        }

        return $this;
    }

    public function removeItem(string|array $id, $parentId = null): self
    {
        if ($parentId && ! isset($this->links[$parentId])) {
            return $this;
        }

        $id = is_array($id) ? $id : func_get_args();
        foreach ($id as $item) {
            if (! $parentId) {
                Arr::forget($this->links, $item);

                break;
            }

            foreach ($this->links[$parentId]['children'] as $key => $child) {
                if ($child['id'] === $item) {
                    Arr::forget($this->links[$parentId]['children'], $key);

                    break;
                }
            }
        }

        return $this;
    }

    public function hasItem(string $id, string|null $parentId = null): bool
    {
        if ($parentId) {
            if (! isset($this->links[$parentId])) {
                return false;
            }

            $id = $parentId . '.children.' . $id;
        }

        return Arr::has($this->links, $id . '.name');
    }

    public function getAll(): Collection
    {
        do_action('render_dashboard_menu');

        $currentUrl = URL::full();

        $prefix = request()->route()->getPrefix();
        if (! $prefix || $prefix === BaseHelper::getAdminPrefix()) {
            $uri = explode('/', request()->route()->uri());
            $prefix = end($uri);
        }

        $routePrefix = '/' . $prefix;

        $links = $this->links;

        $protocol = request()->getScheme() . '://' . BaseHelper::getAdminPrefix();

        foreach ($links as $key => &$link) {
            if ($link['permissions'] && ! Auth::guard()->user()->hasAnyPermission($link['permissions'])) {
                Arr::forget($links, $key);

                continue;
            }

            $link['active'] = $currentUrl == $link['url'] ||
                            (Str::contains((string) $link['url'], $routePrefix) &&
                                ! in_array($routePrefix, ['//', '/' . BaseHelper::getAdminPrefix()]) &&
                                ! Str::startsWith((string) $link['url'], $protocol));
            if (! count($link['children'])) {
                continue;
            }

            $link['children'] = collect($link['children'])
                ->unique(fn ($item) => $item['id'])
                ->sortBy('priority')
                ->toArray();

            foreach ($link['children'] as $subKey => $subMenu) {
                if ($subMenu['permissions'] && ! Auth::guard()->user()->hasAnyPermission($subMenu['permissions'])) {
                    Arr::forget($link['children'], $subKey);

                    continue;
                }

                if ($currentUrl == $subMenu['url'] || Str::contains($currentUrl, (string) $subMenu['url'])) {
                    $link['children'][$subKey]['active'] = true;
                    $link['active'] = true;
                }
            }
        }

        return collect($links)->sortBy('priority');
    }

    public function tap(callable $callback = null): self
    {
        $callback($this);

        return $this;
    }

    public function getItemById(string $itemId): array|null
    {
        if (! $this->hasItem($itemId)) {
            return null;
        }

        return tap($this->links[$this->groupId][$itemId], fn () => $this->default());
    }

    public function getItemsByParentId(string $parentId): Collection|null
    {
        return collect($this->links[$this->groupId] ?? [])
            ->filter(fn ($item) => $item['parent_id'] === $parentId);
    }

    public function beforeRetrieving(Closure $callback): static
    {
        $this->beforeRetrieving[$this->groupId][] = $callback;

        return $this;
    }

    protected function dispatchBeforeRetrieving(): void
    {
        if (empty($this->beforeRetrieving[$this->groupId])) {
            return;
        }

        foreach ($this->beforeRetrieving[$this->groupId] as $callback) {
            call_user_func($callback, $this);
        }
    }

    public function afterRetrieved(Closure $callback): static
    {
        $this->afterRetrieved[$this->groupId][] = $callback;

        return $this;
    }

    public function clearCachesForCurrentUser(): void
    {
        $this->cache->forget($this->cacheKey());
    }

    public function clearCaches(): void
    {
        $this->cache->flush();
    }

    protected function dispatchAfterRetrieved(Collection $menu): void
    {
        if (empty($this->afterRetrieved[$this->groupId])) {
            return;
        }

        foreach ($this->afterRetrieved[$this->groupId] as $callback) {
            call_user_func($callback, $this, $menu);
        }
    }

    protected function parseUrl(string|callable|Closure|null $link): string
    {
        if (empty($link)) {
            return '';
        }

        if (is_string($link)) {
            return $link;
        }

        return call_user_func($link);
    }

    protected function isLocal(): bool
    {
        return ! $this->app->runningInConsole() && $this->app->isLocal();
    }

    protected function getPreviousCalledClass(): string
    {
        return isset(debug_backtrace()[1])
            ? debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
            : '[undefined]';
    }

    protected function cacheKey(): string
    {
        $userType = 'undefined';
        $userKey = 'guest';

        if ($user = $this->request->user()) {
            $userType = $user::class;
            $userKey = $user->getKey();
        }

        return sprintf('dashboard_menu:%s:%s:%s', $this->groupId, $userType, $userKey);
    }

    public function hasCache(): bool
    {
        if (! $this->cacheEnabled) {
            return false;
        }

        return $this->cache->has($this->cacheKey());
    }

    protected function getItemsByGroup(): Collection
    {
        $groupedItems = $this->getGroupedItemsByGroup();

        return $this->getMappedItems($groupedItems[''] ?? collect(), $groupedItems);
    }

    protected function getGroupedItemsByGroup(): Collection
    {
        $items = collect($this->links[$this->groupId] ?? [])
            ->values()
            ->filter(function ($link) {
                $user = $this->request->user();

                if (! empty($link['permissions'])
                    && $user instanceof HasPermissions
                    && ! $user->hasAnyPermission($link['permissions'])) {
                    return false;
                }

                return true;
            });

        $existsIds = $items->pluck('id')->all();

        return $items
            ->mapWithKeys(function ($item) use ($existsIds): array {
                $item['url'] = $this->parseUrl($item['url'] ?? null);

                if (! empty($item['parent_id'])) {
                    if (! in_array($item['parent_id'], $existsIds)) {
                        $item['parent_id'] = null;
                    }

                    if ($item['parent_id'] === 'cms-core-platform-administration') {
                        $item['parent_id'] = 'cms-core-system';
                    }
                }

                return [$item['id'] => $item];
            })
            ->sortBy('priority')
            ->groupBy('parent_id');
    }

    protected function getMappedItems(Collection $items, Collection $groupedItems): Collection
    {
        return $items
            ->reject(function ($item) use ($groupedItems): bool {
                return (
                        empty($item['url']) || $item['url'] === '#' || Str::startsWith($item['url'], 'javascript:void(0)')
                    ) && ! $groupedItems->get($item['id']);
            })
            ->mapWithKeys(function ($item) use ($groupedItems) {
                $groupedItem = $groupedItems->get($item['id']);

                if ($groupedItem instanceof Collection && $groupedItem->isNotEmpty()) {
                    $item['children'] = $this->getMappedItems(
                        $groupedItem,
                        $groupedItems
                    );
                } else {
                    $item['children'] = collect();
                }

                return [$item['id'] => $item];
            });
    }

    protected function applyActive(Collection $menu): Collection
    {
        return $menu->mapWithKeys(function ($item, $key): array {
            return [$key => $this->applyActiveRecursive($item)];
        });
    }

    protected function applyActiveRecursive(array $item): array
    {
        $currentUrl = $this->request->fullUrl();
        $adminPrefix = \Tec\Base\Facades\BaseHelper::getAdminPrefix();
        $url = $item['url'];

        $item['active'] = $currentUrl === $item['url']
            || (
                Str::contains($currentUrl, $url)
                && $url !== url($adminPrefix)
            );

        if ($item['children']->isEmpty()) {
            return $item;
        }

        $children = $item['children']->toArray();

        foreach ($children as &$child) {
            $child = $this->applyActiveRecursive($child);

            if ($child['active']) {
                $item['active'] = true;

                break;
            }
        }

        $item['children'] = collect($children);

        return $item;
    }
}
