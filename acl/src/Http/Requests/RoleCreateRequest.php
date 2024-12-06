<?php

namespace Tec\ACL\Http\Requests;

use Tec\Base\Rules\OnOffRule;
use Tec\Support\Http\Requests\Request;

class RoleCreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120', 'min:3'],
            'description' => ['nullable', 'string', 'string', 'max:255'],
            'is_default' => [new OnOffRule()],
        ];
    }
}
