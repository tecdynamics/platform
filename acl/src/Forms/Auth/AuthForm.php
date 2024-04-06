<?php

namespace Tec\ACL\Forms\Auth;

use Tec\Base\Facades\Assets;
use Tec\Base\Forms\FieldOptions\HtmlFieldOption;
use Tec\Base\Forms\Fields\HtmlField;
use Tec\Base\Forms\FormAbstract;
use Tec\Base\Models\BaseModel;

class AuthForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts('form-validation')
            ->removeStyles([
                'select2',
                'fancybox',
                'spectrum',
                'custom-scrollbar',
                'datepicker',
                'fontawesome',
                'toastr',
            ])
            ->removeScripts([
                'select2',
                'fancybox',
                'cookie',
                'spectrum',
                'toastr',
                'modernizr',
                'excanvas',
                'jquery-waypoints',
                'stickytableheaders',
                'ie8-fix',
            ]);

        $this
            ->model(BaseModel::class)
            ->template('core/acl::auth.form');
    }

    public function heading(string $heading): self
    {
        $this->add(
            'heading',
            HtmlField::class,
            HtmlFieldOption::make()
                ->content(sprintf(
                    '<h2 class="h3 text-center mb-3">%s</h2>',
                    $heading
                ))
                ->toArray()
        );

        return $this;
    }

    public function submitButton(string $label, string|null $icon = null): self
    {
        $this
            ->add(
                'open_wrap_button',
                HtmlField::class,
                HtmlFieldOption::make()->content('<div class="form-footer">')->toArray()
            )
            ->add(
                'submit',
                HtmlField::class,
                HtmlFieldOption::make()->view('core/acl::auth.includes.submit', compact('label', 'icon'))->toArray()
            )
            ->add(
                'close_wrap_button',
                HtmlField::class,
                HtmlFieldOption::make()->content('</div>')->toArray()
            );

        return $this;
    }
}
