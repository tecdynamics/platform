<?php

namespace Tec\ACL\Http\Controllers\Auth;

use Tec\ACL\Http\Requests\ResetPasswordRequest;
use Tec\ACL\Traits\ResetsPasswords;
use Tec\Base\Facades\Assets;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Facades\PageTitle;
use Tec\Base\Http\Controllers\BaseController;
use Tec\JsValidation\Facades\JsValidator;
use Illuminate\Http\Request;

class ResetPasswordController extends BaseController
{
    use ResetsPasswords;

    protected string $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
        $this->redirectTo = BaseHelper::getAdminPrefix();
    }

    public function showResetForm(Request $request, $token = null)
    {
        PageTitle::setTitle(trans('core/acl::auth.reset.title'));

        Assets::addScripts(['jquery-validation', 'form-validation'])
            ->addStylesDirectly('vendor/core/core/acl/css/animate.min.css')
            ->addStylesDirectly('vendor/core/core/acl/css/login.css')
            ->removeStyles([
                'select2',
                'fancybox',
                'spectrum',
                'simple-line-icons',
                'custom-scrollbar',
                'datepicker',
            ])
            ->removeScripts([
                'select2',
                'fancybox',
                'cookie',
            ]);

        $email = $request->input('email');

        $jsValidator = JsValidator::formRequest(ResetPasswordRequest::class);

        return view('core/acl::auth.reset', compact('email', 'token', 'jsValidator'));
    }
}
