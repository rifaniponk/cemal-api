<?php

namespace Cemal\Tests;

abstract class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function getJsonResponse()
    {
        return json_decode($this->response->getContent());
    }

    /**
     * login and return token
     * @param  integer $no tester number. 1 or 2
     * @return string token
     */
    protected function login($no)
    {
        $this->json('POST', '/v1/login', [
            'email'=> 'cemal.tester'.$no.'@rifanmfauzi.com',
            'password'=> '123cemal',
        ]);

        $response = $this->getJsonResponse();

        return $response->data->token;
    }
}
