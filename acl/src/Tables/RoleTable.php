<?php

namespace Tec\ACL\Tables;

use Tec\ACL\Models\Role;
use Tec\Base\Facades\BaseHelper;
use Tec\Table\Abstracts\TableAbstract;
use Tec\Table\Actions\DeleteAction;
use Tec\Table\Actions\EditAction;
use Tec\Table\BulkActions\DeleteBulkAction;
use Tec\Table\BulkChanges\NameBulkChange;
use Tec\Table\Columns\CreatedAtColumn;
use Tec\Table\Columns\FormattedColumn;
use Tec\Table\Columns\IdColumn;
use Tec\Table\Columns\LinkableColumn;
use Tec\Table\Columns\NameColumn;
use Tec\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class RoleTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Role::class)
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('roles.edit'),
                FormattedColumn::make('description')
                    ->title(trans('core/base::tables.description'))
                    ->alignStart()
                    ->withEmptyState(),
                CreatedAtColumn::make(),
                LinkableColumn::make('created_by')
                    ->urlUsing(fn (LinkableColumn $column) => $column->getItem()->author->url)
                    ->title(trans('core/acl::permissions.created_by'))
                    ->width(100)
                    ->getValueUsing(function (LinkableColumn $column) {
                        return BaseHelper::clean($column->getItem()->author->name);
                    })
                    ->externalLink()
                    ->withEmptyState(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('roles.create'))
            ->addActions([
                EditAction::make()->route('roles.edit'),
                DeleteAction::make()->route('roles.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('roles.destroy'))
            ->addBulkChange(NameBulkChange::make())
            ->queryUsing(function (Builder $query) {
                $query
                    ->with('author')
                    ->select([
                        'id',
                        'name',
                        'description',
                        'created_at',
                        'created_by',
                    ]);
            });
    }
}
