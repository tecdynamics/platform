<?php

namespace Tec\Table\Http\Controllers;

use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Table\Abstracts\TableAbstract;
use Tec\Table\Http\Requests\DispatchBulkActionRequest;
use Illuminate\Contracts\Container\BindingResolutionException;

class TableBulkActionController extends TableController
{
    public function __invoke(DispatchBulkActionRequest $request): BaseHttpResponse
    {
        if (
            ! class_exists($request->input('bulk_action_table'))
            || ! class_exists($request->input('bulk_action_target'))
        ) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('core/table::invalid_bulk_action'));
        }

        try {
            /**
             * @var TableAbstract $table
             */
            $table = app()->make($request->input('bulk_action_table'));

            abort_unless($table instanceof TableAbstract, 400);

            return $table->dispatchBulkAction();
        } catch (BindingResolutionException) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Something went wrong.'));
        }
    }
}
