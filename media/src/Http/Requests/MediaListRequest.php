<?php

namespace Tec\Media\Http\Requests;

use Tec\Support\Http\Requests\Request;

class MediaListRequest extends Request
{
    public function rules(): array
    {
        return [
            'folder_id' => ['nullable', 'string'],
        ];
    }
}
