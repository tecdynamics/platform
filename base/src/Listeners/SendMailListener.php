<?php

namespace Tec\Base\Listeners;

use Tec\Base\Events\SendMailEvent;
use Tec\Base\Facades\BaseHelper;
use Tec\Base\Supports\EmailAbstract;
use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMailListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(protected Mailer $mailer)
    {
    }

    public function handle(SendMailEvent $event): void
    {
        try {
					 $bcc = env('EMAIL_BCC',[]);
					 $this->mailer->to($event->to)->bcc($bcc)->send(new EmailAbstract($event->content, $event->title, $event->args));
        } catch (Exception $exception) {
            if ($event->debug) {
                throw $exception;
            }

            BaseHelper::logError($exception);
        }
    }
}
