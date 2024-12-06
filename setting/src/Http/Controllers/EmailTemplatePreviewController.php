<?php

namespace Tec\Setting\Http\Controllers;

use Tec\Base\Facades\BaseHelper;
use Tec\Base\Http\Controllers\BaseController;
use Tec\Setting\Http\Traits\GetEmailTemplateDataTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EmailTemplatePreviewController extends BaseController
{
    use GetEmailTemplateDataTrait;

    public function __invoke(Request $request, string $type, string $module, string $template): View
    {
        [$inputData, $variables] = $this->getData($request, $type, $module, $template);

        if (! empty($inputData)) {
            foreach ($inputData as $key => $value) {
                $inputData[BaseHelper::stringify($key)] = BaseHelper::clean(BaseHelper::stringify($value));
            }
        }

        $routeParams = [$type, $module, $template, 'ref_lang' => request()->input('ref_lang')];

        $backUrl = route('settings.email.template.edit', $routeParams);

        $iframeUrl = route('settings.email.template.iframe', $routeParams);

        return view(
            'core/setting::preview-email',
            compact('variables', 'inputData', 'backUrl', 'iframeUrl')
        );
    }
}
