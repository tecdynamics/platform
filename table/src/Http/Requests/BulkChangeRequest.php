<?php

namespace Tec\Table\Http\Requests;

use Tec\Support\Http\Requests\Request;

class BulkChangeRequest extends Request
{
    public function rules(): array
    {
        return [
            'key' => ['required', 'string'],
            'class' => ['required', 'string'],
        ];
    }
}
