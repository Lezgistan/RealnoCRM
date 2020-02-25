<?php

namespace App\Events\Auth;

use App\Models\Users\User;
use App\Models\Users\UserDoc;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var UserDoc
     */
    protected $document;

    /**
     * @var User
     */
    protected $user;

    /**
     * DocumentCreated constructor.
     * @param UserDoc $document
     * @param User $user
     */
    public function __construct(UserDoc $document, User $user)
    {
        $this->user = $user;
        $this->document = $document;
    }

    public function getUser(): User
    {
        return $this->user;
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
