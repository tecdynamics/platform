<?php

namespace Tec\Setting\Forms;

use Tec\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Tec\Base\Forms\FieldOptions\EmailFieldOption;
use Tec\Base\Forms\FieldOptions\MediaImageFieldOption;
use Tec\Base\Forms\FieldOptions\RepeaterFieldOption;
use Tec\Base\Forms\FieldOptions\TextFieldOption;
use Tec\Base\Forms\Fields\CodeEditorField;
use Tec\Base\Forms\Fields\EmailField;
use Tec\Base\Forms\Fields\MediaImageField;
use Tec\Base\Forms\Fields\RepeaterField;
use Tec\Base\Forms\Fields\TextField;
use Tec\Setting\Http\Requests\EmailTemplateSettingRequest;

class EmailTemplateSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $fields = [
            [
                'type' => 'text',
                'label' => trans('core/setting::setting.email.social_links.name'),
                'attributes' => [
                    'name' => 'name',
                    'value' => null,
                    'options' => [
                        'class' => 'form-control',
                    ],
                ],
            ],
            [
                'type' => 'text',
                'label' => trans('core/setting::setting.email.social_links.url'),
                'attributes' => [
                    'name' => 'url',
                    'value' => null,
                    'options' => [
                        'class' => 'form-control',
                    ],
                ],
            ],
            [
                'type' => 'mediaImage',
                'label' => trans('core/setting::setting.email.social_links.icon_image'),
                'attributes' => [
                    'name' => 'image',
                    'value' => null,
                ],
            ],
        ];

        $this
            ->setUrl(route('settings.email.template.update-settings'))
            ->contentOnly()
            ->setSectionTitle(trans('core/setting::setting.email.email_template_settings'))
            ->setSectionDescription(trans('core/setting::setting.email.email_template_settings_description'))
            ->setValidatorClass(EmailTemplateSettingRequest::class)
            ->setFormOptions(['class' => 'mb-5'])
            ->add(
                'email_template_logo',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('core/setting::setting.email.email_template_logo'))
                    ->value(apply_filters('email_template_logo', setting('email_template_logo')))
                    ->helperText(
                        apply_filters(
                            'email_template_logo_helper_text',
                            trans('core/setting::setting.email.email_template_logo_helper_text'),
                        )

                        . '<br>' . trans('core/setting::setting.email.image_upload_supported')
                    )
                    ->addAttribute('accept', '.png, .jpg, .jpeg, .gif')
                    ->addAttribute('allow_thumb', false)
                    ->toArray()
            )
            ->add(
                'email_template_email_contact',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(trans('core/setting::setting.email.email_template_email_contact'))
                    ->value(setting('email_template_email_contact'))
                    ->helperText(trans('core/setting::setting.email.email_template_email_contact_helper_text'))
            )
            ->add(
                'email_template_copyright_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/setting::setting.email.email_template_copyright_text'))
                    ->value(apply_filters('email_template_copyright_text', setting('email_template_copyright_text')))
                    ->helperText(apply_filters('email_template_copyright_helper_text', trans('core/setting::setting.email.email_template_copyright_text_helper_text')))
                    ->toArray()
            )
            ->add(
                'email_template_custom_css',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('core/setting::setting.email.email_template_custom_css'))
                    ->value(setting('email_template_custom_css'))
                    ->mode('css')
                    ->toArray()
            )
            ->add(
                'email_template_social_links',
                RepeaterField::class,
                RepeaterFieldOption::make()
                    ->label(trans('core/setting::setting.email.email_template_social_links'))
                    ->value(setting('email_template_social_links', []))
                    ->fields($fields)
                    ->toArray()
            )
        ;
    }
}
