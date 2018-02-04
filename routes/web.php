<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'v1', 'namespace' => '\Cemal\Http\Controllers'], function ($app) {
    $app->group(['namespace' => 'Auth'], function ($app) {
        $app->post('register', 'RegisterController@index');
        $app->get('register/{verification_code}', 'RegisterController@verify');

        $app->post('password/reset', 'PasswordController@reset');
        $app->post('password/email', 'PasswordController@requestReset');

        $app->post('login', 'LoginController@login');
        $app->get('whoami', [
            'middleware'=>'auth',
            'uses'=>'LoginController@whoami',
        ]);
        $app->post('logout', [
            'middleware'=>'auth',
            'uses'=>'LoginController@logout',
        ]);
    });
    $app->group(['namespace' => 'Deed', 'prefix' => 'deeds'], function ($app) {
        $app->post('/', [
            'middleware'=>'auth',
            'uses'=>'DeedController@create',
        ]);
        $app->get('/', [
            'middleware'=>'auth',
            'uses'=>'DeedController@all',
        ]);
    });
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});
