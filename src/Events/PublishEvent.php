<?php

namespace LaravelCentrifugo\Events;

use Illuminate\Foundation\Events\Dispatchable;

class PublishEvent
{
    use Dispatchable;

    public function __construct(public int $userId, public string $channel, public array $data)
    {
    }
}