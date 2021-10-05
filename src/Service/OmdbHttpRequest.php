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

        $this->url = 'http://www.omdbapi.com/?';

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

    public function getFilmByTitle(string $filmTitle)
    {

        if ( !$filmTitle || !preg_match("/\S/", $filmTitle) ) {
            throw new \InvalidArgumentException('Argument must be a valid film title of type string!');
        }

        $params['apikey'] = $this->apikey;
        $params['t'] = $filmTitle;

        $urlParams = http_build_query($params);
        $response = $this->client->request('GET', $this->url . $urlParams);

        if($response->getStatusCode() !== 200) {
            throw new FilmNotFoundException('Film not found!');
        }

        $responseBody = json_decode($response->getBody(), true);

        return new FilmResponse($responseBody);

    }

    public function getFilmByImdbId(string $imdbID)
    {

        if (!preg_match("/^t{2}\d{7}$/", $imdbID) ) {
            throw new \InvalidArgumentException('Argument must be a valid imdb id of type string!');
        }

        $params['apikey'] = $this->apikey;
        $params['i'] = $imdbID;

        $urlParams = http_build_query($params);
        $response = $this->client->request('GET', $this->url . $urlParams);

        if($response->getStatusCode() !== 200) {
            throw new FilmNotFoundException('Film not found!');
        }

        $responseBody = json_decode($response->getBody(), true);

        return new FilmResponse($responseBody);

    }



}
