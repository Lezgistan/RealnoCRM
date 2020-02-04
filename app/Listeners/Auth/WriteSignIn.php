<?php

namespace App\Listeners\Auth;

use App\Events\Auth\SignIn;
use App\Models\Users\User;
use App\Models\Users\UserLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WriteSignIn
{
    /**
     * WriteSignIn constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param SignIn $event
     */
    public function handle(SignIn $event)
    {
        $user = $event->getUser();
        $log = new UserLog();
        $log->{'user_id'} = $user->getKey();
        $log->{'event_id'} = 4;
        $log->{'targetable_id'} = $user->getKey();
        $log->{'targetable_type'} = get_class($user);
        $log->{'ip'} = ip2long(request()->getClientIp());
        $log->save();
    }
}
