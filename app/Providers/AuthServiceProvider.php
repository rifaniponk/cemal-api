<?php

namespace Cemal\Providers;

use Cemal\Models\User;
use Cemal\Models\Deed;
use Cemal\Models\UserToken;
use Cemal\Policies\UserPolicy;
use Cemal\Policies\DeedPolicy;
use Cemal\Services\JWTService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->headers->get('Authorization');
            if (! $token) {
                return;
            }

            $token = trim(str_replace('Bearer ', '', $token));

            $jwtService = app(JWTService::class);
            $apiToken = $jwtService->getClaim($token, 'api_token');

            $ut = UserToken::where('api_token', $apiToken)->first();
            if (! $ut) {
                return;
            }

            $now = new \DateTime;
            if (! $ut->expired_at || $ut->expired_at >= $now) {
                if ($ut->expired_at) {
                    $ut->increaseExpired();
                }

                return $ut->user;
            }
        });

        $this->registerPolicies();
    }

    private function registerPolicies()
    {
        \Gate::policy(User::class, UserPolicy::class);
        \Gate::policy(Deed::class, DeedPolicy::class);
    }
}
