<?php

namespace Tec\ACL\Http\Requests;

use Tec\Base\Rules\EmailRule;
use Tec\Support\Http\Requests\Request;

class CreateUserRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:60', 'min:2'],
            'last_name' => ['required', 'string', 'max:60', 'min:2'],
            'email' => ['required', 'min:6', 'max:60', new EmailRule(), 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'username' => ['required', 'string', 'alpha_dash', 'min:4', 'max:30', 'unique:users'],
        ];
    }
}
