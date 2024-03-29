<?php

namespace Tec\ACL\Forms;

use Tec\ACL\Http\Requests\UpdateProfileRequest;
use Tec\ACL\Models\User;
use Tec\Base\Forms\FormAbstract;

class ProfileForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new User())
            ->setFormOption('template', 'core/base::forms.form-no-wrap')
            ->setFormOption('id', 'profile-form')
            ->setFormOption('class', 'row')
            ->setValidatorClass(UpdateProfileRequest::class)
            ->setMethod('PUT')
            ->withCustomFields()
            ->add('first_name', 'text', [
                'label' => trans('core/acl::users.info.first_name'),
                'required' => true,
                'attr' => [
                    'data-counter' => 30,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('last_name', 'text', [
                'label' => trans('core/acl::users.info.last_name'),
                'required' => true,
                'attr' => [
                    'data-counter' => 30,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('username', 'text', [
                'label' => trans('core/acl::users.username'),
                'required' => true,
                'attr' => [
                    'data-counter' => 30,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('email', 'text', [
                'label' => trans('core/acl::users.email'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('core/acl::users.email_placeholder'),
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->setActionButtons(view('core/acl::users.profile.actions')->render());
    }
}
