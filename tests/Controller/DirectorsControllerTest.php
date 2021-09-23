<?php

namespace App\Tests\Controller;


use App\Entity\Director;
use App\Entity\Film;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
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

        $directorName = $this->RandomString(20);

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
        $directorName = $this->RandomString(20);

        $client->request('POST', '/directors', ['director' => ['name' => $directorName]]);

        //test that add director request goes to correct page
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/directors');

        //go back to directors page
        $crawler = $client->request('GET', '/directors');

        //assert that the new director has been added
        $this->stringContains('the director: ' . $directorName . ' has been added!', $client->getResponse()->getContent());
        $this->stringContains('<p>' . $directorName . '</p>', $client->getResponse()->getContent());

        //obtain last director in list
        $lastDirector  = $crawler->filter('p')->last();

        //assert that the last genre in the list matches the one we just added
        $this->assertEquals($directorName, $lastDirector->text());

        //obtain the route of the recently added director delete button
        $directorID = $crawler->filter('.delete-director')->last()->attr('id');

        $directorDeleteRoute = '/directors/delete/' . $directorID;

        //test the delete function
        $client->request('DELETE', $directorDeleteRoute);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/directors');

        $this->assertStringNotContainsString('<p>' . $directorName . '</p>', $client->getResponse()->getContent());

    }

    public function testDeleteAllDirectors() : void
    {

        $client = static::createClient();

        // create and add 10 directors with random names
        $directorNames = [];
        for($i=1;$i<=10;$i++){
            $directorName = $this->RandomString(20);
            $client->request('POST', '/directors', ['director' => ['name' => $directorName]]);
            $directorNames[] = $directorName;
        }

        //go back to directors page
        $client->request('GET', '/directors');

        //test the delete all function
        $client->request('DELETE', '/directors/clear');


        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/directors');

        foreach($directorNames as $directorName) {
            $this->assertStringNotContainsString('<p>' . $directorName . '</p>', $client->getResponse()->getContent());
        }
    }

    public function testDeleteDirectorSetsDirectorToNullOnAssociatedFilmEntity() {

        $client = static::createClient();
        //load entity manager
        $kernel = static::bootKernel();
        $container = $kernel->getContainer();
        $em = self::$container->get(EntityManagerInterface::class);

        // create and add director with random name
        $directorName = $this->RandomString(20);
        $client->request('POST', '/directors', ['director' => ['name' => $directorName]]);

        //go to add film page
        $client->request('GET', '/films/add');

        //add a film with the new director
        $filmTitle = $this->RandomString(20);
        $directorObject = $em->getRepository(Director::class)->findOneBy(['name' => $directorName]);
        $genreObject = new Genre();
        $genreObject->setName('Thriller');
        $client->request( 'POST', '/films/add', [
            'film' => [
                "director" => $directorObject->getId(),
                "description" => $this->RandomString(20),
                "genres" => [
                    $genreObject->getId()
                ],
                "title" => $filmTitle,
                "submit" => ""
            ]
        ]);

        //assert the request redirects to add film page
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/films/add');

        //Go to directors page
        $client->request('GET', '/directors');

        //go back to directors page
        $crawler = $client->request('GET', '/directors');

        //obtain the route of the recently added director delete button
        $directorID = $crawler->filter('.delete-director')->last()->attr('id');
        $directorDeleteRoute = '/directors/delete/' . $directorID;

        //delete the director
        $client->request('DELETE', $directorDeleteRoute);

        //Go back to film index
        $client->request('GET', '/');

        //find the film in the db
        $filmObject = $em->getRepository(Film::class)->findOneBy(['title'=> $filmTitle]);

        //check that the film has actually been added correctly
        $this->assertEquals($filmTitle, $filmObject->getTitle());

        //assert the associated director is now null
        $this->assertNull($filmObject->getDirector());

    }


    public function RandomString(int $length): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

}
