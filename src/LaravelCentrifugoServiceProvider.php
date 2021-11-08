<?php

declare(strict_types=1);

namespace LaravelCentrifugo;

use Illuminate\Console\Application as Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaravelCentrifugo\CLI\CentrifugoGenerate;
use LaravelCentrifugo\CLI\CentrifugoInit;

class LaravelCentrifugoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    private function routeConfiguration(): array
    {
        return [
            'domain' => config('centrifugo.proxy_url'),
            'namespace' => 'Laravel\Centrifugo\Http\Controllers',
            'prefix' => config('centrifugo.path'),
        ];
    }

    public function registerCommands()
    {
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands([
                CentrifugoInit::class,
                CentrifugoGenerate::class
            ]);
        });
    }
}