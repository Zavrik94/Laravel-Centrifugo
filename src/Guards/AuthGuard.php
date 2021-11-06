<?php

namespace LaravelCentrifugo\Guards;

use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Laravel\Passport\Exceptions\InvalidAuthTokenException;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token\RegisteredClaims;

class AuthGuard implements AuthGuardInterface
{
    private Hashids $hashids;

    public function __construct(private Parser $parser, private TokenRepository $repository)
    {
        $salt = config('centrifugo.salt');
        $this->hashids = new Hashids($salt, 8);
    }

    public function auth(Request $request): int
    {
        $data = $request->get('data');
        $token = $data['token'];
        try {
            $plainToken = $this->parser->parse($token);

            $tokenId = $plainToken
                ->claims()->get(RegisteredClaims::ID);
        } catch (Exception) {
            throw new InvalidAuthTokenException();
        }
        $tokenModel = $this->repository->find($tokenId);
        if ($tokenModel === null) {
            throw new InvalidAuthTokenException();
        }
        return $tokenModel->getAttribute('user_id');
    }

    public function encodeUserId(int $userId): string
    {
        return $this->hashids->encode($userId);
    }

    public function decodeUserId(string $hashedUserId): int
    {
        return $this->hashids->decode($hashedUserId)[0];
    }
}