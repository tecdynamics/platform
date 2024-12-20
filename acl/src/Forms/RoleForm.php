<?php

namespace Tec\ACL\Forms;

use Tec\ACL\Http\Requests\RoleCreateRequest;
use Tec\ACL\Models\Role;
use Tec\Base\Facades\Assets;
use Tec\Base\Forms\FieldOptions\DescriptionFieldOption;
use Tec\Base\Forms\FieldOptions\IsDefaultFieldOption;
use Tec\Base\Forms\FieldOptions\NameFieldOption;
use Tec\Base\Forms\Fields\OnOffField;
use Tec\Base\Forms\Fields\TextareaField;
use Tec\Base\Forms\Fields\TextField;
use Tec\Base\Forms\FormAbstract;
use Illuminate\Support\Arr;

class RoleForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addStyles(['jquery-ui', 'jqueryTreeView'])
            ->addScripts(['jquery-ui', 'jqueryTreeView'])
            ->addScriptsDirectly('vendor/core/core/acl/js/role.js');

        $flags = (new Role())->getAvailablePermissions();

        $children = $this->getPermissionTree($flags);
        $active = [];

        /** @var Role $role */
        $role = $this->getModel();

        if ($role && $role->getKey()) {
            $active = array_keys($role->permissions);

            add_filter('base_action_form_actions_extra', function () use ($role) {
                return view('core/acl::roles.includes.extra-actions', compact('role'))->render();
            });
        }

        $this
            ->model(Role::class)
            ->setValidatorClass(RoleCreateRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->maxLength(120)->toArray())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('is_default', OnOffField::class, IsDefaultFieldOption::make()->toArray())
            ->addMetaBoxes([
                'permissions' => [
                    'title' => trans('core/acl::permissions.permission_flags'),
                    'content' => view('core/acl::roles.permissions', compact('active', 'flags', 'children'))->render(),
                    'header_actions' => view('core/acl::roles.permissions-actions')->render(),
                ],
            ]);
    }

    protected function getPermissionTree(array $permissions): array
    {
        $sortedFlag = $permissions;
        sort($sortedFlag);
        $children['root'] = $this->getChildren('root', $sortedFlag);

        foreach (array_keys($permissions) as $key) {
            $childrenReturned = $this->getChildren($key, $permissions);
            if (count($childrenReturned) > 0) {
                $children[$key] = $childrenReturned;
            }
        }

        return $children;
    }

    protected function getChildren(string $parentFlag, array $flags): array
    {
        $newFlags = [];

        foreach ($flags as $item) {
            if (Arr::get($item, 'parent_flag', 'root') === $parentFlag) {
                $newFlags[] = $item['flag'];
            }
        }

        return $newFlags;
    }
}
