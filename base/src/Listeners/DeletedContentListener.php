<?php

namespace Tec\Base\Listeners;

use Tec\Base\Events\DeletedContentEvent;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Models\MetaBox;
use Exception;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, $event->screen, $event->request, $event->data);

            MetaBox::query()->where([
                'reference_id' => $event->data->getKey(),
                'reference_type' => get_class($event->data),
            ])->delete();
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
