<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DirectorsControllerTest extends WebTestCase
{

    public function testManageDirectorsPageGetRequestReturns200() : void
    {

        $client = static::createClient();

        $client->request('GET', '/directors');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testManageDirectorsPagePostRequestReturnsCorrectData() : void
    {

        $client = static::createClient();

        $directorName = $this->RandomString();

        $client->request('POST', '/directors', ['director' => ['name' => $directorName]]);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        self::assertResponseRedirects('/directors');

        $client->request('GET', '/directors');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->stringContains('the director: ' . $directorName . ' has been added!', $client->getResponse()->getContent());

        $this->stringContains('<p>' . $directorName . '</p>', $client->getResponse()->getContent());

    }

    public function testDeleteDirector() : void
    {

        $client = static::createClient();

        // create and add director with random name
        $directorName = $this->RandomString();

        $client->request('POST', '/directors', ['director' => ['name' => $directorName]]);

        //test that add director request goes to correct page
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/directors');

        //go back to genres page
        $crawler = $client->request('GET', '/directors');

        //assert that the new genre has been added
        $this->stringContains('the director: ' . $directorName . ' has been added!', $client->getResponse()->getContent());
        $this->stringContains('<p>' . $directorName . '</p>', $client->getResponse()->getContent());

        //obtain last genre in list
        $lastGenre  = $crawler->filter('p')->last();

        //assert that the last genre in the list matches the one we just added
        $this->assertEquals($directorName, $lastGenre->text());

        //obtain the route of the recently added genres delete button
        $directorDeleteRoute = $crawler->filter('a[type=submit]')->last()->attr('href');

        //test the delete function
        $client->request('GET', $directorDeleteRoute);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/directors');

        $this->assertStringNotContainsString('<p>' . $directorName . '</p>', $client->getResponse()->getContent());

    }

    public function testDeleteAllDirectors() : void
    {

        $client = static::createClient();

        // create and add 10 genres with random names
        $directorNames = [];
        for($i=1;$i<=10;$i++){
            $directorName = $this->RandomString();
            $client->request('POST', '/directors', ['director' => ['name' => $directorName]]);
            $directorNames[] = $directorName;
        }

        //go back to genres page
        $client->request('GET', '/directors');

        //test the delete all function
        $client->request('GET', '/directors/clear');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/directors');

        foreach($directorNames as $directorName) {
            $this->assertStringNotContainsString('<p>' . $directorName . '</p>', $client->getResponse()->getContent());
        }

    }

    public function RandomString(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randstring = '';
        for ($i = 0; $i < 20; $i++) {
            $randstring .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

}
