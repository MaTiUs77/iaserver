<?php

namespace IAServer\Events;

use IAServer\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RedisSend extends Event
{
    use SerializesModels;
    public $canal;
    public $message;
    public $expire;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($canal,$message,$expire=false)
    {
        $this->canal = $canal;
        $this->message = $message;
        $this->expire = $expire;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
