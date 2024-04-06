<?php

namespace Tec\Setting\Http\Controllers;

use Tec\Base\Http\Controllers\BaseController;
use Tec\Base\Supports\Breadcrumb;
use Tec\Setting\Http\Controllers\Concerns\InteractsWithSettings;

abstract class SettingController extends BaseController
{
    use InteractsWithSettings;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/setting::setting.title'), route('settings.index'));
    }
}
