<?php

use Tec\Base\Facades\BaseHelper;
use Tec\Setting\Facades\Setting;
use Tec\Setting\Supports\SettingStore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

if (! function_exists('setting')) {
    function setting(string|null $key = null, $default = null)
    {
        if (! empty($key)) {
            try {
                return app(SettingStore::class)->get($key, $default);
            } catch (Throwable) {
                return $default;
            }
        }

        return Setting::getFacadeRoot();
    }
}

if (! function_exists('get_admin_email')) {
    function get_admin_email(): Collection
    {
        $email = setting('admin_email');

        if (! $email) {
            return collect();
        }

        $email = is_array($email) ? $email : (array)json_decode($email, true);

        return collect(array_filter($email));
    }
}

if (! function_exists('get_setting_email_template_content')) {
    function get_setting_email_template_content(string $type, string $module, string $templateKey, $template_lang = ''): string
    {
        if ($template_lang == null) $template_lang = \Language::getCurrentLocale();

        $defaultPathtrans = storage_path('app/email-templates/' . $module . '/' . $template_lang  . $templateKey . '.tpl');
        $defaultPath = platform_path($type . '/' . $module . '/resources/email-templates/' .$templateKey . '.tpl');
        $storagePath = get_setting_email_template_path($module, $templateKey,$template_lang);

        if ($defaultPathtrans != null && File::exists($defaultPathtrans)) {
            return BaseHelper::getFileData($defaultPathtrans, false);
        }
        if ($storagePath != null && File::exists($storagePath)) {
            return BaseHelper::getFileData($storagePath, false);
        }

        return File::exists($defaultPath) ? BaseHelper::getFileData($defaultPath, false) : '';
    }
}

if (! function_exists('get_setting_email_template_path')) {
    function get_setting_email_template_path(string $module, string $templateKey, $template_lang = ''): string
    {
        if ($template_lang == null) $template_lang = \Language::getCurrentLocale();
//        return storage_path('app/email-templates/' . $module . '/' . $template_lang  . $templateKey . '.tpl');
        $template = apply_filters('setting_email_template_path', "$module/$templateKey.tpl", $module, $templateKey);

        return storage_path('app/email-templates/' . $template_lang  . $template);
    }
}

if (! function_exists('get_setting_email_subject_key')) {
    function get_setting_email_subject_key(string $type, string $module, string $templateKey, $template_lang = ''): string
    {
        if ($template_lang == null) $template_lang = \Language::getCurrentLocale();
        $key = $type . '_' . $module . '_' . $template_lang.$templateKey . '_subject';

        return apply_filters('setting_email_subject_key', $key, $module, $templateKey);
    }
}

if (! function_exists('get_setting_email_subject')) {
    function get_setting_email_subject(string $type, string $module, string $templateKey,$template_lang=''): string
    {
        if ($template_lang == null) $template_lang = \Language::getCurrentLocale();
        $subject = setting(get_setting_email_subject_key($type, $module, $templateKey,$template_lang),
            trans(config($type . '.' . $module . '.email.templates.' . $template_lang .$templateKey . '.subject',
                config($type . '.' . $module . '.email.templates.' .  $templateKey . '.subject',''))));
        return $subject;
    }
}

if (! function_exists('get_setting_email_status_key')) {
    function get_setting_email_status_key($type, $module, $templateKey, $template_lang = '')
    {
        if ($template_lang == null) $template_lang = \Language::getCurrentLocale();
        return $type . '_' . $module . '_' . $template_lang  .$templateKey . '_' . 'status';
    }
}

if (! function_exists('get_setting_email_status')) {
    function get_setting_email_status(string $type, string $module, string $templateKey, $template_lang =''): string
    {
        if ($template_lang == null) $template_lang = \Language::getCurrentLocale();
        $default = config($type . '.' . $module . '.email.templates.' . $template_lang .$templateKey . '.enabled',
            config($type . '.' . $module . '.email.templates.' . $templateKey . '.enabled', true));

        return setting(get_setting_email_status_key($type, $module, $templateKey, $template_lang), $default);
    }
}
