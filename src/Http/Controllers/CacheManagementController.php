<?php

namespace Tec\Base\Http\Controllers;

use Tec\Base\Facades\Assets;
use Tec\Base\Http\Requests\ClearCacheRequest;
use Tec\Base\Services\ClearCacheService;

class CacheManagementController extends BaseController
{
    public function index()
    {
        $this->pageTitle(trans('core/base::cache.cache_management'));

        Assets::addScriptsDirectly('vendor/core/core/base/js/cache.js');

        return view('core/base::system.cache');
    }

    public function destroy(ClearCacheRequest $request, ClearCacheService $clearCacheService)
    {
        switch ($type = $request->input('type')) {
            case 'clear_cms_cache':
                $clearCacheService->clearFrameworkCache();
                $clearCacheService->clearGoogleFontsCache();
                $clearCacheService->clearPurifier();
                $clearCacheService->clearDebugbar();

                break;
            case 'refresh_compiled_views':
                $clearCacheService->clearCompiledViews();

                break;
            case 'clear_config_cache':
                $clearCacheService->clearConfig();

                break;
            case 'clear_route_cache':
                $clearCacheService->clearRoutesCache();

                break;
            case 'clear_log':
                $clearCacheService->clearLogs();

                break;
        }

        return $this
            ->httpResponse()
            ->setMessage(trans("core/base::cache.commands.$type.success_msg"));
    }
}
