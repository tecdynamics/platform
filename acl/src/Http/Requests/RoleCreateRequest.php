<?php

namespace Tec\ACL\Http\Requests;

use Tec\Support\Http\Requests\Request;

class RoleCreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:60|min:3',
            'description' => 'nullable|string|max:255',
        ];
    }
}
