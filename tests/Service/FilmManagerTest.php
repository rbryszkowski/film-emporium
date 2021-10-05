<?php

namespace App\Tests\Service;

use App\Entity\Director;
use App\Entity\Film;
use App\Entity\FilmLog;
use App\Entity\Genre;
use App\Service\FilmManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FilmManagerTest extends WebTestCase
{

    protected $entityManager;
    protected $eventDispatcher;

    public function setUp() : void
    {
        //load entity manager and event dispatcher services at the start of each test
        $kernel = static::bootKernel();
        $container = $kernel->getContainer();
        $this->entityManager = self::$container->get(EntityManagerInterface::class);
        $this->eventDispatcher = self::$container->get(EventDispatcherInterface::class);

    }

    //add film method tests
    public function testAddFilmToDBCorrectlyAddsAFilmToDB()
    {

        //create properties to be used as expected values
        $title = $this->RandomString(20);
        $director = new Director();
        $director->setName($this->RandomString(20));
        $genres = [];
        for($i=1;$i<=3;$i++) {
            $genre = new Genre();
            $genre->setName($this->RandomString(10));
            $genres[] = $genre;
        }
        //create film from these properties
        $film = new Film();
        $film->setTitle($title);
        $film->setDirector($director);
        $film->setGenres($genres);

        //test addFilmToDB
        $filmManager = new FilmManager($this->entityManager, $this->eventDispatcher);
        $filmManager->addFilmToDB($film);

        $filmSearch = $this->entityManager->getRepository(Film::class)->findOneBy([
            'title' => $title
        ]);

        $this->assertEquals($title, $filmSearch->getTitle());
        $this->assertEquals($director->getName(), $filmSearch->getDirector()->getName());
        $filmSearchGenres = $filmSearch->getGenres();
        for($i=0, $iMax = count($genres); $i< $iMax; $i++) {
            $this->assertEquals($genres[$i]->getName(), $filmSearchGenres[$i]->getName());
        }
        $this->assertEquals($director->getName(), $filmSearch->getDirector()->getName());

    }

    public function testGenreIsAddedWhenAddingFilmWithNewGenre() {

        //create properties to be used as expected values
        $title = $this->RandomString(20);
        $director = new Director();
        $director->setName($this->RandomString(20));
        $genre = new Genre();
        $genreName = $this->RandomString(10);
        $genre->setName($genreName);
        //create film from these properties
        $film = new Film();
        $film->setTitle($title);
        $film->setDirector($director);
        $film->setGenres([$genre]);

        //test genre is added
        $filmManager = new FilmManager($this->entityManager, $this->eventDispatcher);
        $filmManager->addFilmToDB($film);

        $genreSearch = $this->entityManager->getRepository(Genre::class)->findOneBy([
            'name' => $genreName
        ]);

        $this->assertEquals($genreName, $genreSearch->getName());

    }

    public function testDirectorIsAddedWhenAddingFilmWithNewDirector() {

        //create properties to be used as expected values
        $title = $this->RandomString(20);
        $director = new Director();
        $directorName = $this->RandomString(20);
        $director->setName($directorName);
        $genre = new Genre();
        $genreName = $this->RandomString(10);
        $genre->setName($genreName);
        //create film from these properties
        $film = new Film();
        $film->setTitle($title);
        $film->setDirector($director);
        $film->setGenres([$genre]);

        //test director is added
        $filmManager = new FilmManager($this->entityManager, $this->eventDispatcher);
        $filmManager->addFilmToDB($film);

        $directorSearch = $this->entityManager->getRepository(Director::class)->findOneBy([
            'name' => $directorName
        ]);

        $this->assertEquals($directorName, $directorSearch->getName());

    }

    public function testCreateAndAddFilmToDBCorrectlyCreatesAndAddsAFilmToDB()
    {

        //create properties to be used as arguments and expected values
        $title = $this->RandomString(20);
        $director = new Director();
        $director->setName($this->RandomString(20));
        $genres = [];
        for($i=1;$i<=3;$i++) {
            $genre = new Genre();
            $genre->setName($this->RandomString(10));
            $genres[] = $genre;
        }

        //test createAndAddFilmToDB
        $filmManager = new FilmManager($this->entityManager, $this->eventDispatcher);
        $filmManager->createAndAddFilmToDB($title, $director, $genres);

        $filmSearch = $this->entityManager->getRepository(Film::class)->findOneBy([
            'title' => $title
        ]);

        $this->assertEquals($title, $filmSearch->getTitle());
        $this->assertEquals($director->getName(), $filmSearch->getDirector()->getName());
        $filmSearchGenres = $filmSearch->getGenres();
        for($i=0, $iMax = count($genres); $i< $iMax; $i++) {
            $this->assertEquals($genres[$i]->getName(), $filmSearchGenres[$i]->getName());
        }
        $this->assertEquals($director->getName(), $filmSearch->getDirector()->getName());

    }

    public function testAddFilmToDBCreatesCorrectFilmLog() {

        //create film to add to DB
        $title = $this->RandomString(20);
        $director = new Director();
        $director->setName($this->RandomString(20));
        $genres = [];
        for($i=1;$i<=3;$i++) {
            $genre = new Genre();
            $genre->setName($this->RandomString(10));
            $genres[] = $genre;
        }
        $film = new Film();
        $film->setTitle($title);
        $film->setDirector($director);
        $film->setGenres($genres);

        //test addFilmToDB
        $filmManager = new FilmManager($this->entityManager, $this->eventDispatcher);
        $filmManager->addFilmToDB($film);

        $filmLogSearch = $this->entityManager->getRepository(FilmLog::class)->findOneBy(['filmTitle' => $title]);

        $this->assertEquals($title, $filmLogSearch->getFilmTitle());
        $this->assertEquals('add', $filmLogSearch->getActiontype());

    }

    //helper methods
    private function RandomString(int $length): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

}
