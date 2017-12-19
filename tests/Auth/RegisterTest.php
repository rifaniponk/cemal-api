<?php

use Cemal\Tests\TestCase;
use Illuminate\Support\Facades\Mail;

class RegisterTest extends TestCase
{
    public function testIndex()
    {
        $data = [
            'email' => 'tester'.str_random(7).'@gmail.com',
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
}
