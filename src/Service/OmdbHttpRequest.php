<?php

namespace App\Service;

use GuzzleHttp\Client;

class OmdbHttpRequest
{

    private $apikey;

    public function __construct() {
        $this->apikey = '34e585c5';
    }

    public function getData(array $params)
    {

        $params['apikey'] = $this->apikey;

        $urlParams = http_build_query($params);
        $url = 'http://www.omdbapi.com/?' . $urlParams;

        $client = new Client();
        $response = $client->request('GET', $url);

        return $response;
    }

    public function getFilm(array $params)
    {

        $response = $this->getData($params);
        return json_decode($response->getBody(), true);

    }
}
