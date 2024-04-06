<?php

namespace Tec\Setting\Http\Controllers;

use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Setting\Forms\CacheSettingForm;
use Tec\Setting\Http\Requests\CacheSettingRequest;

class CacheSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('core/setting::setting.cache.title'));

        return CacheSettingForm::create()->renderForm();
    }

    public function update(CacheSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
