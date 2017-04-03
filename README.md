# Guztav [![Build Status](https://travis-ci.org/joseluisq/guztav.svg?branch=master)](https://travis-ci.org/joseluisq/guztav)
> A small RESTful API client around [PHP Guzzle](https://github.com/guzzle/guzzle).

## Features

- It's only a wrapper around [Guzzle](https://github.com/guzzle/guzzle). It means that you can use the same API like: `Client`, `Request` and `Response` objects.
- Some helper functions for more friendly `Response` data handling like : `$response->toArray()`, `$response->toString()`, etc.
- You can define your `BASE_URI` and `ACCESS_TOKEN` settings via `.env` file. For example using [Dotenv](https://github.com/vlucas/phpdotenv/) package (optional).

## Install

```sh
composer require joseluisq/guztav
```

## Usage

#### Settings via .env file

```sh
# Define your API settings
GUZTAV_BASE_URI='http://localhost:8001/api/v1/'
GUZTAV_ACCESS_TOKEN='eyJ0eXBiOiJKV1QiLCN0bx.....'
```

- `GUZTAV_BASE_URI` : The same Guzzle `base_uri` param for `Client` settings.
- `GUZTAV_ACCESS_TOKEN` : It will be to added to the current header like `Authorization: Bearer ...` (optional)

#### Settings via constructor
You can also pass the same Guzzle options for `Client` object.

```php
<?php

use Guztav\Client;

// Setting the client
$client = new \Guztav\Client([
    // Define these params if you don't use some .env file
    'base_uri' => 'http://localhost:8001/api/v1/',
    'access_token' => 'eyJ0eXBiOiJKV1QiLCN0bx.....', // Optional

    // More options...
    'headers' => ['Accept' => 'application/json'],
    'timeout'  => 2.0,
    ...
]);
```

#### Making a request

```php
<?php

use Guztav\Client;

$client = new \Guztav\Client();

// My GET request
$response = $client->get('client/1');
$string = $response->toString();

// My POST request
$params = [
    'name' => 'Octocat',
    'email' => 'octo.cat@github.com',
];
$array = $client->post('client', ['form_params' => $params])->toArray();

var_dump($string);
var_dump($array);
```

__Note:__ By now, `Guztav` supports `application/json` response data only.

## License
MIT license

© 2017 [José Luis Quintana](https://git.io/joseluisq)
