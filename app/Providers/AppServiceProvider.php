<?php

namespace Cemal\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Cemal\Services\JWTService::class, function () {
            return new \Cemal\Services\JWTService();
        });
    }
}
