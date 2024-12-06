<?php

namespace Tec\Setting\Http\Controllers;

use Tec\Base\Facades\Assets;
use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Setting\Forms\EmailSettingForm;
use Tec\Setting\Http\Requests\EmailSettingRequest;

class EmailSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('core/setting::setting.panel.email'));

        Assets::addScriptsDirectly('vendor/core/core/setting/js/email-template.js');

        $form = EmailSettingForm::create();

        return view('core/setting::email', compact('form'));
    }

    public function update(EmailSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
