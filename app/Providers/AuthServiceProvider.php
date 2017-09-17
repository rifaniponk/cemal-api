<?php

namespace Cemal\Providers;

use Cemal\User;
use Illuminate\Support\ServiceProvider;
use Cemal\Models\UserToken;

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
            if (!$token) return null;

            $ut = UserToken::where('api_token', $token)->first();
            if (!$ut) return null;

            $now = new \DateTime;
            if (!$ut->expired_at || $ut->expired_at <= $now){
                if ($ut->expired_at){
                    $ut->increaseExpired(60);
                }
                return $ut->user;
            }
            return null;
        });
    }
}
