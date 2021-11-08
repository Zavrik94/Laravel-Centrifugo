<?php

namespace LaravelCentrifugo\CLI;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CentrifugoInit extends Command
{
    protected $signature = 'laravel:centrifugo:init';

    public function handle()
    {
        Artisan::call(CentrifugoGenerate::class);
        $this->setUpPhpConfig();
        $this->setUpJsonConfig();
    }

    private function setUpPhpConfig(): void
    {
        File::copy(__DIR__.'/../../stubs/centrifugo.php', config_path('centrifugo.php'));
    }

    private function setUpJsonConfig(): void
    {
        $configJsonPath = __DIR__.'/../../stubs/config.json';
        $configJson = file_get_contents($configJsonPath);
        $config = json_decode($configJson, true);
        $config = $this->setUrls($config);
        $config = $this->setAllowedOrigins($config);
        $config = $this->setAllowedHeaders($config);
        $configJson = json_encode($config);
        file_put_contents($configJsonPath, $configJson);
        $targetPath = base_path('docker/centrifugo');
        if (!is_dir($targetPath)) {
            mkdir($targetPath);
        }
        File::copy(__DIR__.'/../../stubs/config.json', $targetPath . '/config.json');
    }

    private function setUrls(array $config): array
    {
        $appDockerName = config('centrifugo.url');
        $prefix = config('centrifugo.path');
        $config['proxy_connect_endpoint'] = "$appDockerName/$prefix/connect";
        $config['proxy_subscribe_endpoint'] = "$appDockerName/$prefix/subscribe";
        $config['proxy_publish_endpoint'] = "$appDockerName/$prefix/publish";
        return $config;
    }

    private function setAllowedOrigins(array $config): array
    {
        $appUrl = config('app.url');
        $appDockerUrl = config('centrifugo.url');
        preg_match('/https?:\/\/(.*)/', $appUrl, $matches);
        $appHost = $matches[1];
        $wsUrl = "ws://$appHost:8000";
        $dockerUrl = $appDockerUrl;
        $config['allowed_origins'] = [
            $appUrl,
            $wsUrl,
            $dockerUrl
        ];
        return $config;
    }

    private function setAllowedHeaders(array $config): array
    {
        $config['proxy_http_headers'] = config('centrifugo.allowed_headers');
        return $config;
    }
}