<?php

namespace Tec\ACL\Forms;

use Tec\ACL\Http\Requests\UpdateProfileRequest;
use Tec\ACL\Models\User;
use Tec\Base\Forms\FieldOptions\EmailFieldOption;
use Tec\Base\Forms\FieldOptions\TextFieldOption;
use Tec\Base\Forms\Fields\TextField;
use Tec\Base\Forms\FormAbstract;

class ProfileForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(User::class)
            ->template('core/base::forms.form-no-wrap')
            ->setFormOption('id', 'profile-form')
            ->setValidatorClass(UpdateProfileRequest::class)
            ->setMethod('PUT')
            ->columns()
            ->add(
                'first_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::users.info.first_name'))
                    ->required()
                    ->maxLength(30)
                    ->toArray()
            )
            ->add(
                'last_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::users.info.last_name'))
                    ->required()
                    ->maxLength(30)
                    ->toArray()
            )
            ->add(
                'username',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::users.username'))
                    ->required()
                    ->maxLength(30)
                    ->toArray()
            )
            ->add('email', TextField::class, EmailFieldOption::make()->required()->toArray())
            ->setActionButtons(view('core/acl::users.profile.actions')->render());
    }
}
