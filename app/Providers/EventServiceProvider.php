<?php

namespace App\Providers;

use App\Events\AccountError;
use App\Events\ATMError;
use App\Events\FundError;
use App\Listeners\ATMActionListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AccountError::class => [
            ATMActionListener::class
        ],
        ATMError::class => [
            ATMActionListener::class
        ],
        FundError::class => [
            ATMActionListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
