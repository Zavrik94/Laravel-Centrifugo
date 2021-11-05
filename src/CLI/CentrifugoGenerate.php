<?php

namespace LaravelCentrifugo\CLI;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CentrifugoGenerate extends Command
{
    protected $signature = 'laravel:centrifugo:generate';

    public function handle()
    {
        $generated = [
            'CENTRIFUGO_TOKEN_HMAC_SECRET_KEY'  => Str::uuid(),
            'CENTRIFUGO_ADMIN_PASSWORD'         => Str::uuid(),
            'CENTRIFUGO_ADMIN_SECRET'           => Str::uuid(),
            'CENTRIFUGO_API_KEY'                => Str::uuid(),
        ];
        foreach ($generated as $key => $value) {
            echo "$key: $value\n";
            $this->writeNewEnvironmentFileWith($key, $value);
        }
        $configJsonPath = __DIR__.'/../../stubs/config.json';
        $configJson = file_get_contents($configJsonPath);
        $config = json_decode($configJson, true);
        $config['token_hmac_secret_key']    = $generated['CENTRIFUGO_TOKEN_HMAC_SECRET_KEY'];
        $config['admin_password']           = $generated['CENTRIFUGO_ADMIN_PASSWORD'];
        $config['admin_secret']             = $generated['CENTRIFUGO_ADMIN_SECRET'];
        $config['api_key']                  = $generated['CENTRIFUGO_API_KEY'];
        $configJson = json_encode($config);
        file_put_contents($configJsonPath, $configJson);
    }

    protected function writeNewEnvironmentFileWith($key, $value)
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->replacementPattern($key),
            "$key=$value\n",
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    private function replacementPattern($key): string
    {
        return "/$key=.*\n/";
    }
}