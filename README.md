# Laravel-Centrifugo

Steps To install:
1) copy to .env
```
LARAVEL_CENTRIFUGO_DOCKER_DOMAIN=<YOUR_CENTRIFUGO_APP>:<YOUR_CENTRIFUGO_PORT>
CENTRIFUGO_TOKEN_HMAC_SECRET_KEY=20f589ab-b4d8-4ed5-82e3-ff33b99d8b5c
CENTRIFUGO_ADMIN_PASSWORD=8da861d2-6d0f-4a49-8fde-408b74afa635
CENTRIFUGO_ADMIN_SECRET=a1de5910-7614-4c80-883c-d2b9c3951b56
CENTRIFUGO_API_KEY=eb8d0c5e-c7db-4564-9dfd-edfd739ece72
CENTRIFUGO_APP_DOCKER_URL=http://<YOUR_NGINX_APP>
```
2) execute command ```php artisan laravel:centrifugo:init```
3) 
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