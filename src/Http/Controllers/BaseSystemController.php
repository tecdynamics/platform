<?php

namespace Tec\Base\Http\Controllers;

use Tec\Base\Supports\Breadcrumb;

class BaseSystemController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(
                trans('core/base::base.panel.system'),
                route('system.index')
            );
    }
}
