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
}