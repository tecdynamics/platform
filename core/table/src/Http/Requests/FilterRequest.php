<?php

namespace Tec\Table\Http\Requests;

use Tec\Support\Http\Requests\Request;

class FilterRequest extends Request
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'class' => 'required',
        ];
    }
}
