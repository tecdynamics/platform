<?php

namespace Tec\Setting\Http\Controllers;

use Tec\Base\Facades\Assets;
use Illuminate\Contracts\View\View;

class EmailTemplateSettingController extends SettingController
{
    public function index(): View
    {
        $this->pageTitle(trans('core/setting::setting.email.email_templates'));

        Assets::addScriptsDirectly('vendor/core/core/setting/js/email-template.js');

        return view('core/setting::email-templates');
    }
}
