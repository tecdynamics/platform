<?php

namespace Tec\Setting\Http\Controllers;

use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Setting\Forms\DataTableSettingForm;
use Tec\Setting\Http\Requests\DataTableSettingRequest;

class DataTableSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('core/setting::setting.datatable.title'));

        return DataTableSettingForm::create()->renderForm();
    }

    public function update(DataTableSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
