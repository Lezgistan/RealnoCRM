<?php

namespace App\Events\Auth;

use App\Models\Users\File;
use App\Models\Users\User;
use App\Models\Users\UserDoc;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var UserDoc
     */
    protected $document;

    /**
     * DocumentUpdated constructor.
     * @param UserDoc $document
     */
    public function __construct(UserDoc $document)
    {
        $this->document = $document;
    }



    public function getDocument(): UserDoc
    {
        return $this->document;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
