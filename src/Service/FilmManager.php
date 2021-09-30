<?php

namespace App\Service;

use App\Entity\Director;
use App\Entity\Film;
use App\Entity\Genre;
use App\Events\FilmAddedEvent;
use App\Events\FilmDeletedEvent;
use App\Events\FilmUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class FilmManager {

    private $entityManager;
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function addFilmToDB(Film $film) : void
    {
        $director = $film->getDirector();
        $this->entityManager->persist($director);
        $genres = $film->getGenres();
        foreach ($genres as $genre) {
            $this->entityManager->persist($genre);
        }
        $this->entityManager->persist($film);
        $this->entityManager->flush();
        //log the addition
        $filmAddedEvent = new FilmAddedEvent($film);
        $this->eventDispatcher->dispatch($filmAddedEvent, FilmAddedEvent::NAME);

    }

    public function createAndAddFilmToDB(string $title, ?Director $director, ?array $genres, string $description = null, string $imdbID = null) : void
    {

        $film = new Film();
        $film->setTitle($title);
        $film->setDirector($director);
        $film->setGenres($genres);
        $film->setDescription($description);
        $film->setImdbID($imdbID);

        $this->addFilmToDB($film);

    }

    public function updateFilmPrePrepared(Film $film) : void
    {

        $director = $film->getDirector();
        $this->entityManager->persist($director);
        $genres = $film->getGenres();
        foreach ($genres as $genre) {
            $this->entityManager->persist($genre);
        }
        $this->entityManager->persist($film);
        $this->entityManager->flush();

        //log the update
        $filmUpdatedEvent = new FilmUpdatedEvent($film);
        $this->eventDispatcher->dispatch($filmUpdatedEvent, FilmUpdatedEvent::NAME);
    }

    public function updateFilm(Film $film, string $title, Director $director, array $genres, string $description = null, string $imdbID = null) : void
    {

        $film->setTitle($title);
        $film->setDirector($director);
        $film->setGenres($genres);
        $film->setDescription($description);
        $film->setImdbID($imdbID);

        $this->updateFilmPrePrepared($film);

    }

    public function updateFilmWithID(int $id, string $title, Director $director, array $genres, string $description = null, string $imdbID = null) : void
    {

        $film = $this->entityManager->getRepository(Film::class)->find($id);

        if ($film) {
            $this->updateFilm($film, $title, $director, $genres, $description, $imdbID);
        } else {
            dump('Could not find film with that ID!');
        }

    }

    public function deleteFilm(Film $film) : void
    {

        $this->entityManager->remove($film);
        $this->entityManager->flush();
        $deleteFilmEvent = new FilmDeletedEvent($film);
        $this->eventDispatcher->dispatch($deleteFilmEvent, FilmDeletedEvent::NAME);

    }

    public function deleteFilmWithID(int $id) : void
    {
        $film = $this->entityManager->getRepository(Film::class)->find($id);

        if ($film) {
            $this->deleteFilm($film);
        } else {
            dump('Could not find film with that ID!');
        }

    }


}
