<?php

namespace App\Listeners;

use App\Events\ATMActionEvent;
use App\Services\DataReceiverService;

class ATMActionListener
{
    /**
     * Handle the event.
     *
     * @param ATMActionEvent $event
     *
     * @return void
     */
    public function handle(ATMActionEvent $event)
    {
        DataReceiverService::addToOutput($event->message);
    }
}
