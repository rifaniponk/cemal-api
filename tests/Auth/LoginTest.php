<?php
use Cemal\Tests\TestCase;

class LoginTest extends TestCase
{
    public function testWhoamiUnautorized()
    {
        $this->get('/v1/whoami');

        $response = $this->getJsonResponse();

        $this->assertEquals(
            401, $response->status
        );
    }
}
