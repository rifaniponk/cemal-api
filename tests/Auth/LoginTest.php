<?php

use Cemal\Tests\TestCase;
use Cemal\Services\JWTService;

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
        $tokens = $this->getToken();

        $this->get('/v1/whoami', [
            'Authorization' => $tokens[1],
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );

        $this->assertEquals(
            $tokens[2], $response->data->id
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
        $tokens = $this->getToken();

        $this->post('/v1/logout', [], [
            'Authorization' =>  $tokens[1],
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );

        // make sure token has been deleted after logout
        $userTokenCount = DB::table('user_tokens')->where('api_token', $tokens[0])->count();

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

    /**
     * get available token from DB.
     * @return array(api_token, jwt_token, user_id)
     */
    private function getToken()
    {
        $userToken = DB::table('user_tokens')->where('expired_at', '>', new \DateTime)->first();
        $jwtService = app(JWTService::class);

        return [
            $userToken->api_token,
            'Bearer '.$jwtService->getToken(['api_token' => $userToken->api_token]),
            $userToken->user_id,
        ];
    }
}
