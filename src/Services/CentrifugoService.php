<?php

namespace LaravelCentrifugo\Services;

use phpcent\Client;
use Vinkla\Hashids\Facades\Hashids;

final class CentrifugoService
{
    protected Client $client;

    public function __construct()
    {
        $url = config('laravel-centrifugo.url') . '/api';
        $this->client = new Client(
            $url,
            config('laravel-centrifugo.api_key'),
            config('laravel-centrifugo.admin_secret')
        );
    }

    public function getToken(int $userId, int $exp = 1800, array $info = [], array $channels = []): string
    {
        $hashedUserId = $this->getUserId($userId);
        return $this->client->generateConnectionToken($hashedUserId, $exp, $info, $channels);
    }

    public function getUserId(int $userId): string
    {
        return Hashids::encode($userId);
    }

    public function subscribe(string $channel, int $userId):  mixed
    {
        return $this->client->subscribe($channel, $userId);
    }

    public function publish(string $channel, array $data, bool $skipHistory = false): mixed
    {
        return $this->client->publish($channel, $data, $skipHistory);
    }

    public function broadcast(array $channels, array $data, bool $skipHistory = false): mixed
    {
        return $this->client->broadcast($channels, $data, $skipHistory);
    }

    public function unsubscribe(string $channel, int $userId): mixed
    {
        return $this->client->unsubscribe($channel, $userId);
    }

    public function disconnect(int $userId): mixed
    {
        return $this->client->disconnect($userId);
    }

    public function presence(string $channel): mixed
    {
        return $this->client->presence($channel);
    }

    public function presenceStats(string $channel): mixed
    {
        return $this->client->presenceStats($channel);
    }

    public function history(string $channel, int $limit = 0, array $since = [], bool $reverse = false): mixed
    {
        return $this->client->history($channel, $limit, $since, $reverse);
    }

    public function historyRemove(string $channel): mixed
    {
        return $this->client->historyRemove($channel);
    }

    public function channels(string $pattern = ''): mixed
    {
        return $this->client->channels($pattern);
    }

    public function info(): mixed
    {
        return $this->client->info();
    }

    public function setUseAssoc(bool $assoc = true): CentrifugoService
    {
        $this->client->setUseAssoc($assoc);

        return $this;
    }
}