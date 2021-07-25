<?php

namespace App\Events;

use App\Services\DataReceiverService;

class FundError extends ATMActionEvent
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = DataReceiverService::FUNDS_ERROR;
    }
}
