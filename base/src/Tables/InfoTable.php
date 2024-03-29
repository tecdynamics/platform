<?php

namespace Tec\Base\Tables;

use Tec\Base\Supports\SystemManagement;
use Tec\Table\Abstracts\TableAbstract;
use Tec\Table\Columns\Column;
use Illuminate\Http\JsonResponse;

class InfoTable extends TableAbstract
{
    public function setup(): void
    {
        parent::setup();

        $this->view = $this->simpleTableView();
    }

    public function ajax(): JsonResponse
    {
        $composerArray = SystemManagement::getComposerArray();
        $packages = SystemManagement::getPackagesAndDependencies($composerArray['require']);

        return $this
            ->toJson($this->table->of(collect($packages))
            ->editColumn('name', function (array $item) {
                return view('core/base::system.partials.info-package-line', compact('item'))->render();
            })
            ->editColumn('dependencies', function (array $item) {
                return view('core/base::system.partials.info-dependencies-line', compact('item'))->render();
            }));
    }

    public function columns(): array
    {
        return [
            Column::make('name')
                ->title(trans('core/base::system.package_name') . ' : ' . trans('core/base::system.version'))
                ->alignStart(),
            Column::make('dependencies')
                ->title(trans('core/base::system.dependency_name') . ' : ' . trans('core/base::system.version'))
                ->alignStart(),
        ];
    }

    protected function getDom(): string|null
    {
        return $this->simpleDom();
    }
}
