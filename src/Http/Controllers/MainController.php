<?php

declare(strict_types=1);

namespace LaravelCentrifugo\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use LaravelCentrifugo\Events\ConnectEvent;
use LaravelCentrifugo\Events\PublishEvent;
use LaravelCentrifugo\Events\SubscribeEvent;
use LaravelCentrifugo\Exceptions\GuardClassException;
use LaravelCentrifugo\Guards\AuthGuardInterface;
use stdClass;

class MainController extends Controller
{
    private AuthGuardInterface $guard;

    public function __construct()
    {
        $guardClass = config('centrifugo.guard');
        if (!class_exists($guardClass)) {
            throw new GuardClassException();
        }
        $this->guard = app($guardClass);
    }

    #[ArrayShape(['result' => "object"])]
    public function subscribe(Request $request): array
    {
        $userId = $this->guard->decodeUserId($request->get('user'));
        $channel = $request->get('channel');
        event(new SubscribeEvent($userId, $channel));

        return ['result' => $this->getEmptyObject()];
    }

    /**
     * @throws GuardClassException
     */
    #[ArrayShape(['result' => "array"])]
    public function connect(Request $request): array
    {
        $userId = $this->guard->auth($request);
        event(new ConnectEvent($userId));

        return ['result' => [
            'user' => $this->guard->encodeUserId($userId),
        ]];
    }

    #[ArrayShape(['result' => "object"])]
    public function publish(Request $request): array
    {
        $data = $request->get('data');
        $userId = $this->guard->decodeUserId($request->get('user'));
        $channel = $request->get('channel');

        event(new PublishEvent($userId, $channel, $data));
        return ['result' => $this->getEmptyObject()];
    }

    #[Pure]
    public function getEmptyObject(): object
    {
        return new StdClass();
    }
}