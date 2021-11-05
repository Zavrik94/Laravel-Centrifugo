<?php

namespace LaravelCentrifugo\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ConnectEvent
{
    use Dispatchable;

    public function __construct(public int $userId)
    {
    }
}