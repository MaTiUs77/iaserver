<?php

namespace IAServer\Listeners;

use IAServer\Events\RedisSend;

class EventRedisSend
{
    public function __construct()
    {
        //
    }

    public function handle(RedisSend $event)
    {
        try
        {
            \LRedis::set($event->canal,$event->message);
            if($event->expire)
            {
                \LRedis::expire($event->canal,$event->expire);
            }
            \LRedis::publish($event->canal,$event->message);

            return 'done';
        } catch(\Exception $e)
        {
            return ['rediserror'=>$e->getMessage()];
        }
    }
}
