<?php

return [
    'admin_dir' => env('ADMIN_DIR', 'admin'),
    'base_name' => env('APP_NAME', 'Tec Technologies'),
    'logo' => '/vendor/core/core/base/images/logo.png',
    'favicon' => '/vendor/core/core/base/images/favicon.png',
    'editor' => [
        'ckeditor' => [
            'js' => [
                '/vendor/core/core/base/libraries/ckeditor/ckeditor.js',
            ],
        ],
        'tinymce' => [
            'js' => [
                '/vendor/core/core/base/libraries/tinymce/tinymce.min.js',
            ],
        ],
        'primary' => env('PRIMARY_EDITOR', 'ckeditor'),
    ],
    'error_reporting' => [
        'to' => null,
        'via_slack' => env('SLACK_REPORT_ENABLED', false),
        'ignored_bots' => [
            'googlebot',        // Googlebot
            'bingbot',          // Microsoft Bingbot
            'slurp',            // Yahoo! Slurp
            'ia_archiver',      // Alexa
        ],
    ],
    'enable_https_support' => env('ENABLE_HTTPS_SUPPORT', false),
    'force_root_url' => env('FORCE_ROOT_URL'),
    'force_schema' => env('FORCE_SCHEMA'),
    'max_execution_time' => env('CMS_MAX_EXECUTION_TIME', 300),
    'memory_limit' => env('CMS_MEMORY_LIMIT'),
    'date_format' => [
        'date' => env('CMS_DATE_FORMAT', 'Y-m-d'),
        'date_time' => env('CMS_DATE_TIME_FORMAT', 'Y-m-d H:i:s'),
        'js' => [
            'date' => env('CMS_JS_DATE_FORMAT', 'yyyy-mm-dd'),
            'date_time' => env('CMS_JS_DATE_TIME_FORMAT', 'yyyy-mm-dd H:i:s'),
        ],
    ],
    'locale' => env('APP_LOCALE', 'en'),
    'demo' => [
        'account' => [
            'username' => env('CMS_DEMO_ACCOUNT_USERNAME', 'admin'),
            'password' => env('CMS_DEMO_ACCOUNT_PASSWORD', '12345678'),
        ],
    ],
    'google_fonts' => [],

    'custom_google_fonts' => env('CMS_CUSTOM_GOOGLE_FONTS', ''),
    'custom_fonts' => env('CMS_CUSTOM_FONTS', ''),

    'countries' => [
        'AF' => 'Afghanistan',
        'AX' => 'Åland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'PW' => 'Belau',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BQ' => 'Bonaire, Saint Eustatius and Saba',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo (Brazzaville)',
        'CD' => 'Congo (Kinshasa)',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CW' => 'Cura&ccedil;ao',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'CI' => 'Ivory Coast',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KW' => 'Kuwait',
        'XK' => 'Kosovo',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'North Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'KP' => 'North Korea',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barth&eacute;lemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin (French part)',
        'SX' => 'Saint Martin (Dutch part)',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'SM' => 'San Marino',
        'ST' => 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia/Sandwich Islands',
        'KR' => 'South Korea',
        'SS' => 'South Sudan',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom (UK)',
        'US' => 'United States (US)',
        'UM' => 'United States (US) Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'VG' => 'Virgin Islands (British)',
        'VI' => 'Virgin Islands (US)',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'WS' => 'Samoa',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    ],
    'purifier' => [
        'default' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => 'div,b,strong,i,em,u,a[href|title|rel|style|target|dofollow|nofollow],ul,ol,li,p[style],br,span[style],img[width|height|alt|src|style|loading],button,ins[style|data-ad-client|data-ad-slot|data-ad-format|data-full-width-responsive],video[src|type|width|height|preload|controls|autoplay|autostart|poster|id|class,muted],meta[name|content|property],link[media|type|rel|href]',
            'HTML.AllowedElements' => [
                'a',
                'b',
                'blockquote',
                'br',
                'code',
                'em',
                'h1',
                'h2',
                'h3',
                'h4',
                'h5',
                'h6',
                'hr',
                'i',
                'img',
                'li',
                'ol',
                'p',
                'pre',
                's',
                'span',
                'strong',
                'sub',
                'sup',
                'table',
                'tbody',
                'td',
                'th',
                'thead',
                'tr',
                'u',
                'ul',
                'pre',
                'abbr',
                'kbd',
                'var',
                'samp',
                'hr',
                'iframe',
                'figure',
                'figcaption',
                'section',
                'article',
                'aside',
                'blockquote',
                'caption',
                'del',
                'div',
                'button',
                'ins',
                'video',
                'source',
                'meta',
                'link',
                'audio',
            ],
            'HTML.SafeIframe' => 'true',
            // Add to .env if you want to allow all.
            // CMS_IFRAME_FILTER_URL_REGEX=/^(.*)/
            'URI.SafeIframeRegexp' => env('CMS_IFRAME_FILTER_URL_REGEX', '%^(http://|https://|//)(' . env('CMS_IFRAME_ALLOWED_URLS', 'www.youtube.com/embed/|player.vimeo.com/video/|maps.google.com/maps|www.google.com/maps|docs.google.com/|drive.google.com/|view.officeapps.live.com/op/embed.aspx|onedrive.live.com/embed') . ')%'),
            'Attr.AllowedFrameTargets' => ['_blank'],
            'CSS.AllowedProperties' => [
                'font',
                'font-size',
                'font-weight',
                'font-style',
                'font-family',
                'text-decoration',
                'padding-left',
                'color',
                'background-color',
                'text-align',
                'max-width',
                'border',
                'width',
                'line-height',
                'word-spacing',
                'border-style',
                'list-style-type',
                'border-color',
                'height',
                'min-width',
                'min-height',
                'max-height',
                'list-style',
                'margin',
                'margin-bottom',
                'margin-left',
                'margin-right',
                'margin-top',
                'padding',
                'height',
                'line-height',
                'border-collapse',
            ],
            'CSS.MaxImgLength' => null,
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
            'Attr.EnableID' => true,
        ],
        'custom_elements' => [
            ['u', 'Inline', 'Inline', 'Common'],
            ['button', 'Inline', 'Inline', 'Common'],
            ['ins', 'Inline', 'Inline', 'Common'],
            ['meta', 'Inline', 'Empty', 'Common'],
            ['link', 'Inline', 'Empty', 'Common'],
            ['audio', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common'],
        ],
        'custom_attributes' => [
            ['a', 'rel', 'Text'],
            ['a', 'dofollow', 'Bool'],
            ['a', 'nofollow', 'Bool'],
            ['span', 'data-period', 'Text'],
            ['span', 'data-type', 'Text'],
            ['ins', 'data-ad-client', 'Text'],
            ['ins', 'data-ad-slot', 'Text'],
            ['ins', 'data-ad-format', 'Text'],
            ['ins', 'data-ad-full-width-responsive', 'Text'],
            ['img', 'data-src', 'Text'],
            ['img', 'loading', 'Text'],
            ['video', 'autoplay', 'Bool'],
            ['meta', 'name', 'Text'],
            ['meta', 'content', 'Text'],
            ['meta', 'property', 'Text'],
            ['link', 'media', 'Text'],
            ['link', 'type', 'Text'],
            ['link', 'rel', 'Text'],
            ['link', 'href', 'Text'],
            ['link', 'color', 'Text'],
            ['audio', 'controls', 'Bool'],
            ['div', 'data-bs-theme', 'Text'],
            ['button', 'data-bb-toggle', 'Text'],
            ['button', 'data-value', 'Text'],
        ],
    ],
    'enable_system_updater' => env('CMS_ENABLE_SYSTEM_UPDATER', true),
    'phone_validation_rule' => env('CMS_PHONE_VALIDATION_RULE', 'min:8|max:15|regex:/^([0-9\s\-\+\(\)]*)$/'),
    'disable_verify_csrf_token' => env('CMS_DISABLE_VERIFY_CSRF_TOKEN', false),
    'enable_less_secure_web' => env('CMS_ENABLE_LESS_SECURE_WEB', false),
    'db_strict_mode' => env('DB_STRICT', true),
    'enable_ini_set' => env('CMS_ENABLE_INI_SET', true),
    'upgrade_php_require_disabled' => env('CMS_UPGRADE_PHP_REQUIRE_DISABLED', false),
    'enabled_cleanup_database' => env('CMS_ENABLED_CLEANUP_DATABASE', false),
    'hide_cleanup_system_menu' => env('CMS_HIDE_CLEANUP_SYSTEM_MENU', false),
    'hide_activated_license_info' => env('CMS_HIDE_ACTIVATED_LICENSE_INFO', false),
    'google_fonts_url' => env('CMS_GOOGLE_FONTS_URL', 'https://fonts.bunny.net'),
    'google_fonts_enabled' => env('CMS_GOOGLE_FONTS_ENABLED', true),
    'google_fonts_enabled_cache' => env('CMS_GOOGLE_FONTS_ENABLED_CACHE', true),
    'using_uuids_for_id' => env('CMS_USING_UUIDS_FOR_ID', false),
    'using_ulids_for_id' => env('CMS_USING_ULIDS_FOR_ID', false),
    'type_id' => env('CMS_USING_TYPE_ID', 'BIGINT'),
    'csv_import_input_encoding' => env('CMS_CSV_IMPORT_INPUT_ENCODING', 'UTF-8'),
    'google_fonts_key' => env('CMS_GOOGLE_FONTS_KEY'),
];
