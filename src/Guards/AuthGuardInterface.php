<?php

namespace LaravelCentrifugo\Guards;

use Illuminate\Http\Request;

interface AuthGuardInterface
{
    public function auth(Request $request): int;

    public function encodeUserId(int $userId): string;

    public function decodeUserId(string $hashedUserId): int;
}