<?php

namespace App\Providers;

use App\Events\LoginEvent;
use App\Listeners\LoginTrackingListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        LoginEvent::class => [
            LoginTrackingListener::class
        ],
    ];
}
