<?php

namespace Tec\Table\BulkActions;

use Tec\Base\Events\DeletedContentEvent;
use Tec\Base\Exceptions\DisabledInDemoModeException;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Http\Responses\BaseHttpResponse;
use Tec\Base\Models\BaseModel;
use Tec\Table\Abstracts\TableBulkActionAbstract;
use Illuminate\Database\Eloquent\Model;

class DeleteBulkAction extends TableBulkActionAbstract
{
    public function __construct()
    {
        $this
            ->label(trans('core/table::table.delete'))
            ->confirmationModalButton(trans('core/table::table.delete'))
            ->beforeDispatch(function () {
                if (BaseHelper::hasDemoModeEnabled()) {
                    throw new DisabledInDemoModeException();
                }
            });
    }


    public function dispatch(BaseModel|Model $model, array $ids): BaseHttpResponse
    {
        $model->newQuery()->whereIn('id', $ids)->each(function (BaseModel $item) {
            $item->delete();

            event(new DeletedContentEvent('', request(), $item));
        });

        return (new BaseHttpResponse())
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
