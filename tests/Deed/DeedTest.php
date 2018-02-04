<?php

use Cemal\Tests\TestCase;

class DeedTest extends TestCase
{
    public function testCreate()
    {
        $data = [
            "title" => "Puasa senin kamis",
            "description" => "Puasa setiap hari senin & kamis",
            "public" => true
        ];

        $this->json('POST', '/v1/deeds', $data, [
            'Authorization' => $this->login(2)
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            201, $response->status
        );

        // non-admin shouldnt be able to create public deeds
        $this->assertEquals(
            false, $response->data->public
        );
    }

    public function testCreatePublic()
    {
        $data = [
            "title" => "Puasa senin kamis",
            "description" => "Puasa setiap hari senin & kamis",
            "public" => true
        ];
        
        // call endpoint as admin
        $this->json('POST', '/v1/deeds', $data, [
            'Authorization' => $this->login(1)
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            201, $response->status
        );

        // admin should be able to create public deeds
        $this->assertEquals(
            true, $response->data->public
        );
    }

    public function testCreateInvalid()
    {
        $data = [
            "title" => "",
            "description" => "Puasa setiap hari senin & kamis",
            "public" => true
        ];
        
        // call endpoint as admin
        $this->json('POST', '/v1/deeds', $data, [
            'Authorization' => $this->login(1)
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            400, $response->status
        );
    }

    public function testCreateUnathorized()
    {
        $data = [
            "title" => "",
            "description" => "Puasa setiap hari senin & kamis",
            "public" => true
        ];
        
        // call endpoint as admin
        $this->json('POST', '/v1/deeds', $data);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            401, $response->status
        );
    }

    public function testAllNonAdmin()
    {
        // call endpoint as non-admin, tester 2
        $this->json('GET', '/v1/deeds', [], [
            'Authorization' => $this->login(2)
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );

        // normal user only can view his deeds and public deeds
        foreach ($response->data as $deed) {
            if ($deed->user_id === '7239a85c-931e-4a8b-8f71-fbeec729a404'){
                $this->assertEquals(
                    false, $deed->public
                );
            } else {
                $this->assertEquals(
                    true, $deed->public
                );
            }
        }

    }

    public function testAllNonAdminCantSeeAnotherDeed()
    {
        // call endpoint as non-admin, tester 3
        $this->json('GET', '/v1/deeds', [], [
            'Authorization' => $this->login(3)
        ]);

        $response = $this->getJsonResponse();

        $this->assertEquals(
            200, $response->status
        );

        // normal user only can view his deeds and public deeds
        foreach ($response->data as $deed) {
            // tester3 havent not created deed, so his lists should be all public
            $this->assertEquals(
                true, $deed->public
            );
        }
    }
}