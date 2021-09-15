<?php

namespace App\Service;

use App\Exceptions\FilmNotFoundException;
use App\Models\FilmResponse;
use GuzzleHttp\Client;

class OmdbHttpRequest
{

    /**
     * @var string
     */
    private $url;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $apikey;

    public function __construct(string $apikey, Client $client=null) {

//        $this->url = 'http://www.omdbapi.com/?';

        $this->url = 'http://www.jhgeergjhwgfj.com/?';

        $this->apikey = $apikey;

        if ($client !== null)
        {
            $this->client = $client;
        }
        else
        {
            $this->client = new Client();
        }

    }

    public function getFilm(string $filmTitle)
    {
        $params['apikey'] = $this->apikey;
        $params['t'] = $filmTitle;

        $urlParams = http_build_query($params);
        $response = $this->client->request('GET', $this->url . $urlParams);

        $responseBody = json_decode($response->getBody(), true);

        if($response->getStatusCode() !== 200) {
            throw new FilmNotFoundException('Film not found!');
        }

        return new FilmResponse($responseBody);

    }



}
