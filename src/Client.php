<?php
/*
 * This file is part of Guztav.
 *
 * (c) Jose Luis Quintana <https://git.io/joseluisq>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Guztav;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Client class extented from \GuzzleHttp\Client.
 */
class Client extends \GuzzleHttp\Client
{
    private $response;
    private $base_uri = '';
    private $access_token = '';
    private $refresh_token = '';

    public function __construct($options = [])
    {
        $defaults = $this->prepareDefaults($options);
        parent::__construct($defaults);
    }

    private function prepareDefaults($options = [])
    {
        $this->base_uri = $options['base_uri']
                        = $options['base_uri'] ?? getenv('GUZTAV_BASE_URI');

        $this->access_token = $options['access_token']
                            = $options['access_token'] ?? getenv('GUZTAV_ACCESS_TOKEN');
        unset($options['access_token']);

        // Set JSON Accept header by default
        $options = array_merge([
            'headers' => ['Accept' => 'application/json']
        ], $options);

        // Set the Authorization header
        if ($this->access_token) {
            $options['headers']['Authorization'] = "Bearer {$this->access_token}";
        }

        $this->options = $options;

        return $options;
    }

    private function prepareResponse(ResponseInterface $response)
    {
        $this->response = new \Guztav\Response(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
        return $this->response;
    }

    private function getOptions()
    {
        return $this->options;
    }

    public function send(RequestInterface $request, array $options = [])
    {
        $this->request = $request;
        return $this->prepareResponse(parent::send($request, $options));
    }

    public function get($uri = '', array $options = [])
    {
        return $this->send(new \Guztav\Request('GET', $uri, $options));
    }

    public function post($uri = '', array $options = [])
    {
        return $this->send(new \Guztav\Request('POST', $uri, $options));
    }

    public function put($uri = '', array $options = [])
    {
        return $this->send(new \Guztav\Request('PUT', $uri, $options));
    }

    public function patch($uri = '', array $options = [])
    {
        return $this->send(new \Guztav\Request('PATCH', $uri, $options));
    }

    public function delete($uri = '', array $options = [])
    {
        return $this->send(new \Guztav\Request('DELETE', $uri, $options));
    }

    public function getRequest()
    {
        return $this->request;
    }
}
