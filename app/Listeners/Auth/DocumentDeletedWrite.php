<?php

namespace App\Listeners\Auth;

use App\Events\Auth\DocumentDeleted;
use App\Models\Users\UserLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DocumentDeletedWrite
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
     * @param DocumentDeleted $event
     * @return void
     */
    public function handle(DocumentDeleted $event)
    {
        $user = $event->getUser();
        $document = $event->getDocument();
        $log = new UserLog();
        $log->{'user_id'} = \Auth::id();
        $log->{'event_id'} = 8;
        $log->{'targetable_id'} = $document->getId();
        $log->{'targetable_type'} = get_class($document);
        $log->{'ip'} = ip2long(request()->getClientIp());
        $log->save();
    }
}
