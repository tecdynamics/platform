<?php

namespace Tec\ACL\Tables;

use Tec\ACL\Models\Role;
use Tec\Base\Facades\BaseHelper;
use Tec\Table\Abstracts\TableAbstract;
use Tec\Table\Actions\DeleteAction;
use Tec\Table\Actions\EditAction;
use Tec\Table\BulkActions\DeleteBulkAction;
use Tec\Table\Columns\Column;
use Tec\Table\Columns\CreatedAtColumn;
use Tec\Table\Columns\FormattedColumn;
use Tec\Table\Columns\IdColumn;
use Tec\Table\Columns\LinkableColumn;
use Tec\Table\Columns\NameColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class RoleTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Role::class)
            ->addActions([
                EditAction::make()->route('roles.edit'),
                DeleteAction::make()->route('roles.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->with('author')
            ->select([
                'id',
                'name',
                'description',
                'created_at',
                'created_by',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('roles.edit'),
            FormattedColumn::make('description')
                ->title(trans('core/base::tables.description'))
                ->alignStart()
                ->withEmptyState(),
            CreatedAtColumn::make(),
            LinkableColumn::make('created_by')
                ->route('users.profile.view')
                ->title(trans('core/acl::permissions.created_by'))
                ->width(100)
                ->getValueUsing(function (Column $column) {
                    /**
                     * @var Role $item
                     */
                    $item = $column->getItem();

                    return BaseHelper::clean($item->author->name);
                })
                ->withEmptyState(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('roles.create'), 'roles.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('roles.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
        ];
    }
}
