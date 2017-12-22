<?php

use Cemal\Tests\TestCase;

class PasswordTest extends TestCase
{
	const USER_EMAIL = 'cemal.tester1@rifanmfauzi.com';

	public function testRequestReset()
	{
		$this->json('POST', '/v1/password/email', [
        	'email'=> self::USER_EMAIL,
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );
	}

	public function testRequestResetNotFound()
	{
		$this->json('POST', '/v1/password/email', [
        	'email'=> 'oanasjdndjasjdsjd@yumba.com',
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            404, $response->status
        );
	}

	/**
     * @depends testRequestReset
     */
	public function testReset()
	{
        $pswdToken = DB::table('password_resets')->where('email', self::USER_EMAIL)->first();

		$this->json('POST', '/v1/password/reset', [
        	'email'=> self::USER_EMAIL,
        	'password' => '123cemaludin',
        	'password_confirmation' => '123cemaludin',
        	'token' => $pswdToken->token,
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );

        // reset token password must be cleared out 
        $counter = DB::table('password_resets')->where('email', self::USER_EMAIL)->count();

        $this->assertEquals(
            0, $counter
        );
	}

	/**
     * @depends testRequestReset
     */
	public function testResetBadInput()
	{
		$this->json('POST', '/v1/password/reset', [
        	'password' => '123cemaludin',
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            400, $response->status
        );
	}

	/**
     * @depends testRequestReset
     */
	public function testResetTokenWrong()
	{
		$this->json('POST', '/v1/password/reset', [
        	'email'=> self::USER_EMAIL,
        	'password' => '123cemaludin',
        	'password_confirmation' => '123cemaludin',
        	'token' => str_random(20),
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            404, $response->status
        );
	}
}