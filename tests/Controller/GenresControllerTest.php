<?php

namespace App\Tests\Controller;


use App\Controller\GenresController;

use App\Entity\Genre;
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

        // create and add genre with random name
        $genreName = bin2hex(random_bytes(10));
        $client->request('POST', '/genres', ['genre' => ['name' => $genreName]]);

        //test that it goes to correct page
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/genres');

        //go back to genres page
        $crawler = $client->request('GET', '/genres');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //assert that the new genre has been added
        $this->stringContains('the genre: ' . $genreName . ' has been added!', $client->getResponse()->getContent());
        $this->stringContains('<p>' . $genreName . '</p>', $client->getResponse()->getContent());

        //obtain last genre in list
        $lastGenre  = $crawler->filter('p')->last();

        //assert that the last genre in the list matches the one we just added
        $this->assertEquals($genreName, $lastGenre->text());

        //obtain the route of the recently added genres delete button
        $genreDeleteRoute = $crawler->filter('a[type=submit]')->last()->attr('href');

        //test the delete function
        $client->request('GET', $genreDeleteRoute);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/genres');

        $this->assertStringNotContainsString('<p>' . $genreName . '</p>', $client->getResponse()->getContent());

    }

}
