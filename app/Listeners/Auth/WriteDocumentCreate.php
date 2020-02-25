<?php

namespace App\Listeners\Auth;

use App\Events\Auth\DocumentCreated;
use App\Models\Users\UserDoc;
use App\Models\Users\UserLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WriteDocumentCreate
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
     * @param DocumentCreated $event
     * @return void
     */
    public function handle(DocumentCreated $event)
    {
        $document = $event->getDocument();
        $log = new UserLog();
        $log->{'user_id'} = \Auth::id();
        $log->{'event_id'} = 6;
        $log->{'targetable_id'} = $document->getId();
        $log->{'targetable_type'} = get_class($document);
        $log->{'ip'} = ip2long(request()->getClientIp());
        $log->save();
    }
}
