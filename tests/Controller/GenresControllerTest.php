<?php

namespace App\Tests\Controller;


use App\Controller\GenresController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GenresControllerTest extends WebTestCase
{

    public function testManageGenresPageGetRequestReturns200() : void
    {

        $client = static::createClient();

        $client->request('GET', '/genres');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testManageGenresPagePostRequestReturnsCorrectData() : void
    {

        $client = static::createClient();

        $genreName = bin2hex(random_bytes(10));

        $client->request('POST', '/genres', ['genre' => ['name' => $genreName]]);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        self::assertResponseRedirects('/genres');

        $client->request('GET', '/genres');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->stringContains('the genre: ' . $genreName . ' has been added!', $client->getResponse()->getContent());

        $this->stringContains('<p>' . $genreName . '</p>', $client->getResponse()->getContent());

    }

    public function testDeleteGenre() : void
    {

        $client = static::createClient();

        $genreName = bin2hex(random_bytes(10));

        $client->request('POST', '/genres', ['genre' => ['name' => $genreName]]);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        self::assertResponseRedirects('/genres');

        $client->request('GET', '/genres');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->stringContains('the genre: ' . $genreName . ' has been added!', $client->getResponse()->getContent());

        $this->stringContains('<p>' . $genreName . '</p>', $client->getResponse()->getContent());

    }

}
