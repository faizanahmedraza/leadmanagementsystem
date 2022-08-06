<?php

namespace App\Listeners;

use App\Events\LoginEvent;

use  App\Http\Wrappers\LogifyWrapper;

class LoginTrackingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoginEvent  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        $event->user->setAttribute( 'last_login' , \Carbon\Carbon::now())->save();
    }
}
