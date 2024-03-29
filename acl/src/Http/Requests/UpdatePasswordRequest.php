<?php

namespace Tec\ACL\Http\Requests;

use Tec\Support\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UpdatePasswordRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'password' => 'required|min:6|max:60',
            'password_confirmation' => 'same:password',
        ];

        if (Auth::guard()->user() && Auth::guard()->user()->isSuperUser()) {
            return $rules;
        }

        return ['old_password' => 'required|min:6|max:60'] + $rules;
    }
}
