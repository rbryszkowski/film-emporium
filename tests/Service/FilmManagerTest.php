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

    //add film method tests
    public function testAddFilmToDBCorrectlyAddsAFilmToDB()
    {

        //load entity manager and event dispatcher services to be used as arguments in FilmManager
        $kernel = static::bootKernel();
        $container = $kernel->getContainer();
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $eventDispatcher = self::$container->get(EventDispatcherInterface::class);

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
        $filmManager = new FilmManager($entityManager, $eventDispatcher);
        $filmManager->addFilmToDB($film);

        $filmSearch = $entityManager->getRepository(Film::class)->findOneBy([
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

        //load entity manager and event dispatcher services to be used as arguments in FilmManager
        $kernel = static::bootKernel();
        $container = $kernel->getContainer();
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $eventDispatcher = self::$container->get(EventDispatcherInterface::class);

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
        $filmManager = new FilmManager($entityManager, $eventDispatcher);
        $filmManager->addFilmToDB($film);

        $genreSearch = $entityManager->getRepository(Genre::class)->findOneBy([
            'name' => $genreName
        ]);

        $this->assertEquals($genreName, $genreSearch->getName());

    }

    public function testDirectorIsAddedWhenAddingFilmWithNewDirector() {

        //load entity manager and event dispatcher services to be used as arguments in FilmManager
        $kernel = static::bootKernel();
        $container = $kernel->getContainer();
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $eventDispatcher = self::$container->get(EventDispatcherInterface::class);

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
        $filmManager = new FilmManager($entityManager, $eventDispatcher);
        $filmManager->addFilmToDB($film);

        $directorSearch = $entityManager->getRepository(Director::class)->findOneBy([
            'name' => $directorName
        ]);

        $this->assertEquals($directorName, $directorSearch->getName());

    }

    public function testCreateAndAddFilmToDBCorrectlyCreatesAndAddsAFilmToDB()
    {
        //load entity manager and event dispatcher services to be used as arguments in FilmManager
        $kernel = static::bootKernel();
        $container = $kernel->getContainer();
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $eventDispatcher = self::$container->get(EventDispatcherInterface::class);

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
        $filmManager = new FilmManager($entityManager, $eventDispatcher);
        $filmManager->createAndAddFilmToDB($title, $director, $genres);

        $filmSearch = $entityManager->getRepository(Film::class)->findOneBy([
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

        //load entity manager and event dispatcher services to be used as arguments in FilmManager
        $kernel = static::bootKernel();
        $container = $kernel->getContainer();
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $eventDispatcher = self::$container->get(EventDispatcherInterface::class);

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
        $filmManager = new FilmManager($entityManager, $eventDispatcher);
        $filmManager->addFilmToDB($film);

        $filmLogSearch = $entityManager->getRepository(FilmLog::class)->findOneBy(['filmTitle' => $title]);

        $this->assertEquals($title, $filmLogSearch->getFilmTitle());
        $this->assertEquals('add', $filmLogSearch->getActiontype());

    }

    //helper methods
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
