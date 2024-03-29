<?php

namespace Tec\ACL\Http\Controllers;

use Tec\ACL\Events\RoleAssignmentEvent;
use Tec\ACL\Events\RoleUpdateEvent;
use Tec\ACL\Forms\RoleForm;
use Tec\ACL\Http\Requests\AssignRoleRequest;
use Tec\ACL\Http\Requests\RoleCreateRequest;
use Tec\ACL\Models\Role;
use Tec\ACL\Models\User;
use Tec\ACL\Tables\RoleTable;
use Tec\Base\Facades\PageTitle;
use Tec\Base\Forms\FormBuilder;
use Tec\Base\Http\Controllers\BaseController;
use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Base\Supports\Helper;

class RoleController extends BaseController
{
    public function index(RoleTable $dataTable)
    {
        PageTitle::setTitle(trans('core/acl::permissions.role_permission'));

        return $dataTable->renderTable();
    }

    public function destroy(Role $role, BaseHttpResponse $response)
    {
        $role->delete();

        Helper::clearCache();

        return $response->setMessage(trans('core/acl::permissions.delete_success'));
    }

    public function edit(Role $role, FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('core/acl::permissions.details', ['name' => $role->name]));

        return $formBuilder->create(RoleForm::class, ['model' => $role])->renderForm();
    }

    public function update(Role $role, RoleCreateRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default')) {
            Role::query()->where('id', '!=', $role->getKey())->update(['is_default' => 0]);
        }

        $role->name = $request->input('name');
        $role->permissions = $this->cleanPermission((array)$request->input('flags', []));
        $role->description = $request->input('description');
        $role->updated_by = $request->user()->getKey();
        $role->is_default = $request->input('is_default');
        $role->save();

        Helper::clearCache();

        event(new RoleUpdateEvent($role));

        return $response
            ->setPreviousUrl(route('roles.index'))
            ->setNextUrl(route('roles.edit', $role->getKey()))
            ->setMessage(trans('core/acl::permissions.modified_success'));
    }

    protected function cleanPermission(array $permissions): array
    {
        if (! $permissions) {
            return [];
        }

        $cleanedPermissions = [];
        foreach ($permissions as $permissionName) {
            $cleanedPermissions[$permissionName] = true;
        }

        return $cleanedPermissions;
    }

    public function create(FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('core/acl::permissions.create_role'));

        return $formBuilder->create(RoleForm::class)->renderForm();
    }

    public function store(RoleCreateRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default')) {
            Role::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $role = Role::query()->create([
            'name' => $request->input('name'),
            'permissions' => $this->cleanPermission((array)$request->input('flags', [])),
            'description' => $request->input('description'),
            'is_default' => $request->input('is_default'),
            'created_by' => $request->user()->getKey(),
            'updated_by' => $request->user()->getKey(),
        ]);

        return $response
            ->setPreviousUrl(route('roles.index'))
            ->setNextUrl(route('roles.edit', $role->getKey()))
            ->setMessage(trans('core/acl::permissions.create_success'));
    }

    public function getDuplicate(Role $role, BaseHttpResponse $response)
    {
        $duplicatedRole = Role::query()->create([
            'name' => $role->name . ' (Duplicate)',
            'slug' => $role->slug,
            'permissions' => $role->permissions,
            'description' => $role->description,
            'created_by' => $role->created_by,
            'updated_by' => $role->updated_by,
        ]);

        return $response
            ->setPreviousUrl(route('roles.edit', $role->getKey()))
            ->setNextUrl(route('roles.edit', $duplicatedRole->getKey()))
            ->setMessage(trans('core/acl::permissions.duplicated_success'));
    }

    public function getJson(): array
    {
        $pl = [];
        foreach (Role::query()->get() as $role) {
            $pl[] = [
                'value' => $role->getKey(),
                'text' => $role->name,
            ];
        }

        return $pl;
    }

    public function postAssignMember(AssignRoleRequest $request, BaseHttpResponse $response): BaseHttpResponse
    {
        /**
         * @var User $user
         */
        $user = User::query()->findOrFail($request->input('pk'));

        /**
         * @var Role $role
         */
        $role = Role::query()->findOrFail($request->input('value'));

        $user->roles()->sync([$role->getKey()]);

        event(new RoleAssignmentEvent($role, $user));

        return $response;
    }
}
