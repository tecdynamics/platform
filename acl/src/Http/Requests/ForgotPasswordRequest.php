<?php

namespace Tec\ACL\Http\Requests;

use Tec\Base\Rules\EmailRule;
use Tec\Support\Http\Requests\Request;

class ForgotPasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', new EmailRule()],
        ];
    }
}
