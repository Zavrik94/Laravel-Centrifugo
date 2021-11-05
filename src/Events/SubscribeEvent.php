<?php

namespace LaravelCentrifugo\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SubscribeEvent
{
    use Dispatchable;

    public function __construct(public int $userId, public string $channel)
    {
    }
}