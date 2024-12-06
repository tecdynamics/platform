<?php

namespace Tec\JsValidation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Tec\JsValidation\Javascript\JavascriptValidator make(array $rules, array $messages = [], array $customAttributes = [], string|null $selector = null)
 * @method static \Tec\JsValidation\Javascript\JavascriptValidator formRequest($formRequest, $selector = null)
 * @method static \Tec\JsValidation\Javascript\JavascriptValidator validator(\Illuminate\Validation\Validator $validator, string|null $selector = null)
 *
 * @see \Tec\JsValidation\JsValidatorFactory
 */
class JsValidator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'js-validator';
    }
}
