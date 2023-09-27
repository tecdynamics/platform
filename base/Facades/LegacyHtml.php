<?php
/**
 *  ****************************************************************
 *  *** DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER. ***
 *  ****************************************************************
 *  Copyright © 2023 TEC-Dynamics LTD <support@tecdynamics.org>.
 *  All rights reserved.
 *  This software contains confidential proprietary information belonging
 *  to Tec-Dynamics Software Limited. No part of this information may be used, reproduced,
 *  or stored without prior written consent of Tec-Dynamics Software Limited.
 * @Author    : Michail Fragkiskos
 * @Created at: 27/09/2023 at 08:59
 * @Interface     : LegacyHtml
 * @Package   : tec_new
 * @package Tec\Base\Facades
 */

namespace Tec\Base\Facades;

class LegacyHtml
{
    /**
     * Build a single attribute element.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected static function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        // This will convert HTML attributes such as "required" to a correct
        // form instead of using incorrect numerics.
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }

        if (is_array($value) && $key === 'class') {
            return 'class="' . implode(' ', $value) . '"';
        }

        if (! is_null($value)) {
            return $key . '="' . e($value, false) . '"';
        }
    }
    public static function addattributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = self::attributeElement($key, $value);

            if (! is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }
    /**
     * Generate an HTML image element.
     *
     * @param string $url
     * @param string $alt
     * @param array  $attributes
     * @param bool   $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public static function image($url, $alt = null, $attributes = [], $secure = null)
    {
        $attributes['alt'] = $alt;

        return Html()->img($url)->attributes($attributes)->toHtml();

    }
}
