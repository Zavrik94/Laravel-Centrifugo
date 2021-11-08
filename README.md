# Laravel-Centrifugo For Docker

This package is an easier way for use [Centrifugo v3](https://centrifugal.dev/) with [Laravel Framework](https://laravel.com/) and [Docker](https://www.docker.com/).
It realises proxy connections inside docker for trigger events when we have some data in WebSocket Connection.


### Install
1) Copy to .env
```
CENTRIFUGO_APP_DOCKER_URL=http://<YOUR_CENTRIFUGO_CONTAINER_NAME>:<YOUR_CENTRIFUGO_PORT>
CENTRIFUGO_PROXY_DOCKER_URL=http://<YOUR_CONTAINER_NAME_FOR_PROXY_REDIRECT>
CENTRIFUGO_TOKEN_HMAC_SECRET_KEY=37246e8e-6943-444f-9f5c-c8d3241af361
CENTRIFUGO_ADMIN_PASSWORD=c32c421a-2123-4e8b-b063-c849e6dab599
CENTRIFUGO_ADMIN_SECRET=d37f2351-24e6-4373-9462-c2030e0eaf40
CENTRIFUGO_API_KEY=719521ac-72ce-4621-ad36-5fe0c8cbfd47
```
2) execute command ```php artisan laravel:centrifugo:init``` and clear cache.
3) Add to your ```docker-compose``` file this container
```
  centrifugo:
    container_name: centrifugo
    image: centrifugo/centrifugo:v3
    volumes:
      - ./docker/centrifugo/config.json:/centrifugo/config.json
    command: centrifugo -c config.json
    ports:
      - '8000:8000'
    ulimits:
      nofile:
        soft: 65535
        hard: 65535
    networks:
      - <YOUR DOCKER NETWORK>
```
### Usage
#### Autogenerate config
Command ```php artisan laravel:centrifugo:init``` generate ```config/centrifugo.php``` for Laravel and ```docker/centrifugo/config.json``` for Centrifugo.
It Generates for you default proxy routes by ```.env``` and centrifugo keys.
#### Security
By Default, it uses Laravel Passport Token for identify users and possible to connect Centrifugo.
You can write your own Guard by implement ```AuthGuardInterface```.
```
interface AuthGuardInterface
{
    public function auth(Request $request): int;

    public function encodeUserId(int $userId): string;

    public function decodeUserId(string $hashedUserId): int;
}
```

_auth(Request $request): int_

Give you Request with body that you can send, and you must return userID by whom WebSocket was connected.

_encodeUserId(int $userId): string_

Encoding User ID in unique string for security display User ID.
By Default in use ```Hashids``` library.

_decodeUserId(string $hashedUserId): int_

Decoding Hashed User ID for correctly setUp User ID in events.

#### Proxy Events
Centrifugo has 4 proxy routes.
We use only 3 of them because of refresh proxy unused by AuthGuard Authentication.
You can select which of Users can connect to Centrifugo by Guarding You Application Token.

_connect_

After Successfully Guarded Your token it dispatches ```ConnectEvent``` with UserID.

_subscribe_

Dispatch ```SubscribeEvent``` with UsedID and Channel Name after User successfully subscribed on Channel

_publish_

Dispatch ```PublishEvent``` with UserID, Channel and Published Data after User successfully published data in Channel


#### FrontEnd usage

You must use default [Centrifugo JS library](https://github.com/centrifugal/centrifuge-js).
Example with default BearerToken Connection:
```
const centrifuge = new Centrifuge("ws://localhost:8000/connection/websocket");
centrifuge.setConnectData({
    token: bearerToken
});
centrifuge.connect();
let subscription  = centrifuge.subscribe(channel, function(ctx) {
    container.innerHTML = ctx.data.value;
    document.title = ctx.data.value;
});
```

By ```centrifuge.setConnectData``` you can send Any data to you Request Body in AuthGuard.