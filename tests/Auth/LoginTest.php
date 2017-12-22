<?php

use Cemal\Tests\TestCase;

class LoginTest extends TestCase
{
    public function testLogin()
    {
        $this->json('POST', '/v1/login', [
            'email'=> 'cemal.tester2@rifanmfauzi.com',
            'password'=> '123cemal',
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );
    }

    public function testLoginBadInput()
    {
        $this->json('POST', '/v1/login', [
            'email'=> 'cemal.tester2@rifanmfauzi.com',
            'password'=> 'adadasdasdasd',
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            400, $response->status
        );
    }

    /**
     * @depends testLogin
     */
    public function testWhoami()
    {
        $userToken = DB::table('user_tokens')->where('expired_at', '>', new \DateTime)->first();

        $this->get('/v1/whoami', [
            'Authorization' => $userToken->api_token,
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );

        $this->assertEquals(
            $userToken->user_id, $response->data->id
        );
    }

    /**
     * @depends testLogin
     */
    public function testWhoamiUnautorized()
    {
        $this->get('/v1/whoami');

        $response = $this->getJsonResponse();

        $this->assertEquals(
            401, $response->status
        );
    }

    /**
     * @depends testLogin
     */
    public function testLogout()
    {
        $userToken = DB::table('user_tokens')->where('expired_at', '>', new \DateTime)->first();

        $this->post('/v1/logout', [], [
            'Authorization' => $userToken->api_token,
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );

        // make sure token has been deleted after logout
        $userTokenCount = DB::table('user_tokens')->where('api_token', $userToken->api_token)->count();

        $this->assertEquals(
            0, $userTokenCount
        );
    }

    /**
     * @depends testLogin
     */
    public function testLogoutUnautorized()
    {
        $this->post('/v1/logout');

        $response = $this->getJsonResponse();

        $this->assertEquals(
            401, $response->status
        );
    }
}
