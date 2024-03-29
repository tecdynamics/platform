<?php

namespace Tec\ACL\Forms;

use Tec\ACL\Http\Requests\CreateUserRequest;
use Tec\ACL\Models\Role;
use Tec\ACL\Models\User;
use Tec\Base\Forms\FormAbstract;

class UserForm extends FormAbstract
{
    public function buildForm(): void
    {
        $roles = Role::query()->pluck('name', 'id');

        $defaultRole = $roles->where('is_default', 1)->first();

        $this
            ->setupModel(new User())
            ->setValidatorClass(CreateUserRequest::class)
            ->withCustomFields()
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
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
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
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
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('password', 'password', [
                'label' => trans('core/acl::users.password'),
                'required' => true,
                'attr' => [
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('password_confirmation', 'password', [
                'label' => trans('core/acl::users.password_confirmation'),
                'required' => true,
                'attr' => [
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('rowClose3', 'html', [
                'html' => '</div>',
            ])
            ->add('role_id', 'customSelect', [
                'label' => trans('core/acl::users.role'),
                'attr' => [
                    'class' => 'form-control roles-list',
                ],
                'choices' => ['' => trans('core/acl::users.select_role')] + $roles->all(),
                'default_value' => $defaultRole ? $defaultRole->id : null,
            ])
            ->setBreakFieldPoint('role_id');
    }
}
