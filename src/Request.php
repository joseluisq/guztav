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

/**
 * Request class extented from \GuzzleHttp\Psr7\Request.
 */
class Request extends \GuzzleHttp\Psr7\Request
{
    public function __construct(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $version = '')
    {
        parent::__construct($method, $uri, $headers, $body, $version);
    }
}
