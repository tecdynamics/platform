<?php

namespace Tec\Base\Http\Requests;

use Tec\Support\Http\Requests\Request;

class SelectSearchAjaxRequest extends Request
{
    public function rules(): array
    {
        return [
            'search' => ['required', 'string'],
            'page' => ['required', 'integer'],
        ];
    }
}
