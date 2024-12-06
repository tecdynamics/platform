<?php

namespace Tec\Setting\Http\Controllers;

use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Setting\Http\Requests\EmailTemplateChangeStatusRequest;

class EmailTemplateStatusController extends SettingController
{
    public function __invoke(EmailTemplateChangeStatusRequest $request): BaseHttpResponse
    {
        $this->saveSettings([$request->input('key') => $request->input('value')]);

        $message = $request->boolean('value')
            ? trans('core/setting::setting.email.turn_on_success_message')
            : trans('core/setting::setting.email.turn_off_success_message');

        return $this
            ->httpResponse()
            ->setMessage($message);
    }
}
