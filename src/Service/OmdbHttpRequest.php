<?php

namespace App\Service;

use App\Models\FilmResponse;
use GuzzleHttp\Client;

class OmdbHttpRequest
{

    private $apikey;

    public function __construct(string $apikey) {
        $this->apikey = $apikey;
    }

    public function getFilm(array $params)
    {
        $params['apikey'] = $this->apikey;

        $urlParams = http_build_query($params);
        $url = 'http://www.omdbapi.com/?' . $urlParams;

        $client = new Client();
        $response = $client->request('GET', $url);

        return new FilmResponse($response);

    }

}
