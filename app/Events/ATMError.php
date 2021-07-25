<?php

namespace App\Events;

use App\Services\DataReceiverService;

class ATMError extends ATMActionEvent
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = DataReceiverService::ATM_ERROR;
    }
}
