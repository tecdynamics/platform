<?php

namespace Tec\Setting\Http\Requests;

use Tec\Base\Rules\OnOffRule;
use Tec\Support\Http\Requests\Request;
use DateTimeZone;
use Illuminate\Validation\Rule;

class GeneralSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'admin_email' => ['nullable', 'array'],
            'admin_email.*' => ['nullable', 'email'],
            'time_zone' => Rule::in(DateTimeZone::listIdentifiers()),
            'enable_send_error_reporting_via_email' => [new OnOffRule()],
        ];
    }
}
