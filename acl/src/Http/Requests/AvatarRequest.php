<?php

namespace Tec\ACL\Http\Requests;

use Tec\Media\Facades\RvMedia;
use Tec\Support\Http\Requests\Request;

class AvatarRequest extends Request
{
    public function rules(): array
    {
        return [
            'avatar_file' => RvMedia::imageValidationRule(),
            'avatar_data' => 'required|string',
        ];
    }
}
