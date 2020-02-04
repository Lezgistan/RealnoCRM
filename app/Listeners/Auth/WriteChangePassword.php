<?php

namespace App\Listeners\Auth;

use App\Events\Auth\ChangePassword;
use App\Models\Users\User;
use App\Models\Users\UserLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WriteChangePassword
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
     * @param  ChangePassword  $event
     * @return void
     */
    public function handle(ChangePassword $event)
    {
        $user = $event->getUser();
        $log = new UserLog();
        $log->{'user_id'} = \Auth::id();
        $log->{'event_id'} = 5;
        $log->{'targetable_id'} = $user->getKey();
        $log->{'targetable_type'} = get_class($user);
        $log->{'ip'} = ip2long(request()->getClientIp());
        $log->save();
    }
}
