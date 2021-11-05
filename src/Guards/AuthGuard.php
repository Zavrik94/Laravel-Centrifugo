<?php

namespace LaravelCentrifugo\Guards;

use Exception;
use Illuminate\Http\Request;
use Laravel\Passport\Exceptions\InvalidAuthTokenException;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token\RegisteredClaims;
use Vinkla\Hashids\Facades\Hashids;

class AuthGuard implements AuthGuardInterface
{
    public function __construct(private Parser $parser, private TokenRepository $repository)
    {
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
        return Hashids::encode($userId);
    }

    public function decodeUserId(string $hashedUserId): int
    {
        return Hashids::decode($hashedUserId)[0];
    }
}