<?php

namespace App\Listeners\Auth;

use App\Models\Users\User;
use App\Events\Auth\UserUpdate;
use App\Models\Users\UserLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WriteUserUpdate
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
     * @param UserUpdate $event
     * @return void
     */
    public function handle(UserUpdate $event)
    {
        $user = $event->getUser();
        $log = new UserLog();
        $log->{'user_id'} = \Auth::id();
        $log->{'event_id'} = 2;
        $log->{'targetable_id'} = $user->getKey();
        $log->{'targetable_type'} = get_class($user);
        $log->{'ip'} = ip2long(request()->getClientIp());
        $log->save();
    }
}
