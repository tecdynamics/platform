<?php

namespace Tec\Setting\Http\Requests;

use Tec\Base\Rules\OnOffRule;
use Tec\Support\Http\Requests\Request;

class EmailTemplateChangeStatusRequest extends Request
{
    public function rules(): array
    {
        return [
            'key' => ['required', 'string'],
            'value' => [new OnOffRule()],
        ];
    }
}
