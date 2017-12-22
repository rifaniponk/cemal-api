<?php

use Cemal\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class RegisterTest extends TestCase
{
    public function testIndex()
    {
        $email = 'tester'.str_random(7).'@gmail.com';
        
        $data = [
            'email' => $email,
            'password' => '123cemal',
            'password_confirmation' => '123cemal',
            'name' => 'John Doe',
            'phone' => '093839434',
            'address' => 'Jalan Anomali no 90',
            'avatar' => 'https://openclipart.org/image/2400px/svg_to_png/177482/ProfilePlaceholderSuit.png',
            'biography' => 'Iam Software Engineer who lives in Indonesia',
        ];

        // mock mail facade
        Mail::fake();

        $this->json('POST', '/v1/register', $data);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            201, $response->status
        );
    }

    public function testIndexBadInput()
    {
        $data = [
            'password' => '123cemal',
            'password_confirmation' => '123cemaludin',
            'phone' => '093839434',
            'address' => 'Jalan Anomali no 90',
        ];

        $this->json('POST', '/v1/register', $data);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            400, $response->status
        );

        $this->assertEquals(
            true, property_exists($response->data, 'name') 
        );

        $this->assertEquals(
            true, property_exists($response->data, 'email') 
        );

        $this->assertEquals(
            true, property_exists($response->data, 'password') 
        );
    }

    /**
     * @depends testIndex
     */
    public function testVerify()
    {
        // get unverified user
        $user = DB::table('users')->where('verified', false)->first();

        $this->json('GET', '/v1/register/'.$user->verification_code);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );

        // fetch updated user data
        $user = DB::table('users')->where('id', $user->id)->first();

        // make sure user has been verified after the call
        $this->assertEquals(
            true, $user->verified
        );
    }

    /**
     * @depends testIndex
     */
    public function testVerifyNotFound()
    {
        $this->json('GET', '/v1/register/'.substr(md5(date('Y-m-d H:i:s')), 0, 20));

        $response = $this->getJsonResponse();

        $this->assertEquals(
            404, $response->status
        );
    }
}
