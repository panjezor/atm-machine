<?php

namespace App\Events;

use App\Services\DataReceiverService;

class AccountError extends ATMActionEvent
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = DataReceiverService::ACCOUNT_ERROR;
    }
}
