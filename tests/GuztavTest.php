<?php
/*
 * This file is part of Guztav.
 *
 * (c) Jose Luis Quintana <https://git.io/joseluisq>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Guztav\Test;

use Guztav\Client;
use Guztav\Request;
use Guztav\Response;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

/**
 * Guztav Test suite.
 *
 * @package Guztav
 * @author Jose Luis Quintana <https://git.io/joseluisq>
 */
class GuztavTest extends TestCase
{
    public function clientMaker($isDotenv = false)
    {
        $settings = [];

        if ($isDotenv) {
            $dotenv = new Dotenv(__DIR__);
            $dotenv->load();
        } else {
            $settings = [
                'base_uri' => 'http://jsonplaceholder.typicode.com/',
                'access_token' => 'eyJ0eXBiOiJKV1QiLCN0bx.....'
            ];
        }

        return new Client($settings);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage URI must be a string or UriInterface
     */
    public function testValidatesSettings()
    {
        $client = new Client();
        $client->get();
    }

    public function testClient()
    {
        $client = $this->clientMaker();

        $this->assertInstanceOf(\GuzzleHttp\Client::class, $client);

        return $client;
    }

    /**
     * @depends testClient
     */
    public function testResponse(Client $client)
    {
        $response = $client->get('posts/1');

        $this->assertInstanceOf(\GuzzleHttp\Psr7\Request::class, $client->getRequest());
        $this->assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        return $response;
    }

    /**
     * @depends testResponse
     */
    public function testResponseArrayData(Response $response)
    {
        $data = $response->toArray();

        $this->assertTrue(is_array($data));
        $this->assertEquals(4, count($data));
        $this->assertArrayHasKey('id', $data);

        return $data;
    }

    /**
     * @depends testResponse
     * @depends testResponseArrayData
     */
    public function testResponseStringData(Response $response, array $data)
    {
        $string = $response->toString();

        $this->assertJsonStringEqualsJsonString(json_encode($data), $string);
    }

    public function testClientWithDotenvSettings()
    {
        $client = $this->clientMaker(true);

        $this->assertInstanceOf(\GuzzleHttp\Client::class, $client);

        return $client;
    }

    /**
     * @depends testClientWithDotenvSettings
     */
    public function testClientWithDotenvSettingsRequest(Client $client)
    {
        $response = $client->get('posts/1');

        $this->assertInstanceOf(\GuzzleHttp\Psr7\Request::class, $client->getRequest());
        $this->assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
