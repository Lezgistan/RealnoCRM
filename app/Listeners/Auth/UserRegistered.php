<?php

namespace App\Listeners\Auth;

use App\Models\Users\User;
use App\Models\Users\UserLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRegistered
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        /**
         * @var User
         */
        $user = $event->user;
        UserLog::create([
            'user_id'=>auth()->id(),
            'ip'=>ip2long(request()->ip()),
            'event_id'=>1,
            'targetable_id'=>$user->getKey(),
            'targetable_type'=>User::class,
            'payload'=>UserLog::unsetUnnecessaryFields($user->toArray()),
        ]);
    }
}
