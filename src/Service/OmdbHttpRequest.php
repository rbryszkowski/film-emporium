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

        if (!preg_match("/\S/", $filmTitle)) {
            throw new \InvalidArgumentException('Argument must be a valid film title!');
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

    public function getFilmById(string $id)
    {

        if (!preg_match("/t{2}\d{7}/", $id)) {
            throw new \InvalidArgumentException('Argument must be a valid id with format (tt1234567)');
        }

        $params['apikey'] = $this->apikey;
        $params['i'] = $id;

        $urlParams = http_build_query($params);
        $response = $this->client->request('GET', $this->url . $urlParams);

        if($response->getStatusCode() !== 200) {
            throw new FilmNotFoundException('Film not found!');
        }

        $responseBody = json_decode($response->getBody(), true);

        return new FilmResponse($responseBody);

    }


}
