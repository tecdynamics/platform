<?php

namespace Tec\ACL\Tables;

use Tec\ACL\Enums\UserStatusEnum;
use Tec\ACL\Models\User;
use Tec\ACL\Services\ActivateUserService;
use Tec\Base\Events\UpdatedContentEvent;
use Tec\Base\Exceptions\DisabledInDemoModeException;
use Tec\Base\Facades\Assets;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Models\BaseQueryBuilder;
use Tec\Table\Abstracts\TableAbstract;
use Tec\Table\Actions\Action;
use Tec\Table\Actions\DeleteAction;
use Tec\Table\Actions\EditAction;
use Tec\Table\BulkActions\DeleteBulkAction;
use Tec\Table\BulkChanges\CreatedAtBulkChange;
use Tec\Table\BulkChanges\EmailBulkChange;
use Tec\Table\BulkChanges\NameBulkChange;
use Tec\Table\BulkChanges\StatusBulkChange;
use Tec\Table\Columns\CreatedAtColumn;
use Tec\Table\Columns\EmailColumn;
use Tec\Table\Columns\FormattedColumn;
use Tec\Table\Columns\LinkableColumn;
use Tec\Table\Columns\YesNoColumn;
use Tec\Table\HeaderActions\CreateHeaderAction;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserTable extends TableAbstract
{
    public function setup(): void
    {
        Assets::addScripts(['bootstrap-editable', 'jquery-ui'])
            ->addStyles(['bootstrap-editable']);

        $this
            ->model(User::class)
            ->displayActionsAsDropdown(false)
            ->addColumns([
                LinkableColumn::make('username')
                    ->urlUsing(fn (LinkableColumn $column) => $column->getItem()->url)
                    ->title(trans('core/acl::users.username'))
                    ->alignStart(),
                EmailColumn::make()->linkable(),
                FormattedColumn::make('role_name')
                    ->title(trans('core/acl::users.role'))
                    ->searchable(false)
                    ->orderable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        $role = $item->roles->first();

                        if (! $this->hasPermission('users.edit')) {
                            return $role?->name ?: trans('core/acl::users.no_role_assigned');
                        }

                        return view('core/acl::users.partials.role', compact('item', 'role'))->render();
                    }),
                CreatedAtColumn::make(),
                FormattedColumn::make('status_name')
                    ->title(trans('core/base::tables.status'))
                    ->width(100)
                    ->searchable(false)
                    ->orderable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        if ($column->getItem()->activations()->where('completed', true)->exists()) {
                            return UserStatusEnum::ACTIVATED()->toHtml();
                        }

                        return UserStatusEnum::DEACTIVATED()->toHtml();
                    }),
                YesNoColumn::make('super_user')
                    ->title(trans('core/acl::users.is_super'))
                    ->width(100),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('users.create'))
            ->when(Auth::guard()->user()->isSuperUser(), function () {
                $this->addActions([
                    Action::make('make-super')
                        ->route('users.make-super')
                        ->color('success')
                        ->label(trans('core/acl::users.make_super'))
                        ->renderUsing(fn (Action $action) => $action->getItem()->isSuperUser() ? '' : null),
                    Action::make('remove-super')
                        ->route('users.remove-super')
                        ->label(trans('core/acl::users.remove_super'))
                        ->color('warning')
                        ->renderUsing(fn (Action $action) => ! $action->getItem()->isSuperUser() ? '' : null),
                ]);
            })
            ->addActions([
                Action::make('extra')->renderUsing(function (Action $action) {
                    return apply_filters(ACL_FILTER_USER_TABLE_ACTIONS, '', $action->getItem());
                }),
                EditAction::make()
                    ->url(fn (Action $action) => $action->getItem()->url)
                    ->permission('users.edit'),
                DeleteAction::make()->route('users.destroy')->permission('users.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()
                    ->permission('users.destroy')
                    ->beforeDispatch(function (User $user, array $ids) {
                        foreach ($ids as $id) {
                            if (Auth::guard()->id() == $id) {
                                abort(403, trans('core/acl::users.delete_user_logged_in'));
                            }

                            /**
                             * @var User $user
                             */
                            $user = User::query()->findOrFail($id);
                            if (! Auth::guard()->user()->isSuperUser() && $user->isSuperUser()) {
                                abort(403, trans('core/acl::users.cannot_delete_super_user'));
                            }
                        }
                    }),
            ])
            ->addBulkChanges([
                NameBulkChange::make()
                    ->name('username')
                    ->title(trans('core/acl::users.username')),
                EmailBulkChange::make(),
                StatusBulkChange::make()
                    ->choices(UserStatusEnum::labels())
                    ->validate(['required', Rule::in(UserStatusEnum::values())]),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'username',
                        'email',
                        'updated_at',
                        'created_at',
                        'super_user',
                    ])
                    ->with(['roles']);
            });
    }

    public function htmlDrawCallbackFunction(): ?string
    {
        return parent::htmlDrawCallbackFunction() . 'Tec.initEditable()';
    }

    public function saveBulkChanges(array $ids, string $inputKey, ?string $inputValue): bool
    {
        if (BaseHelper::hasDemoModeEnabled()) {
            throw new DisabledInDemoModeException();
        }

        if ($inputKey === 'status') {
            $hasWarning = false;

            $service = app(ActivateUserService::class);

            foreach ($ids as $id) {
                if ($inputValue == UserStatusEnum::DEACTIVATED && Auth::guard()->id() == $id) {
                    $hasWarning = true;
                }

                $user = $this->getModel()->query()->findOrFail($id);

                if (! $user instanceof User) {
                    continue;
                }

                if ($inputValue == UserStatusEnum::ACTIVATED) {
                    $service->activate($user);
                } else {
                    $service->remove($user);
                }

                event(new UpdatedContentEvent(USER_MODULE_SCREEN_NAME, request(), $user));
            }

            if ($hasWarning) {
                throw new Exception(trans('core/acl::users.lock_user_logged_in'));
            }

            return true;
        }

        return parent::saveBulkChanges($ids, $inputKey, $inputValue);
    }

    public function applyFilterCondition(
        EloquentBuilder|QueryBuilder|EloquentRelation $query,
        string $key,
        string $operator,
        ?string $value
    ): EloquentRelation|EloquentBuilder|QueryBuilder {
        if ($key === 'status' && $value) {

            if ($value == UserStatusEnum::ACTIVATED) {
                return $query->whereHas('activations', fn (BaseQueryBuilder $query) => $query->where('completed', true));
            }

            return $query->whereDoesntHave('activations');
        }

        return parent::applyFilterCondition($query, $key, $operator, $value);
    }
}
