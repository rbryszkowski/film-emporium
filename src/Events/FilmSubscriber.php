<?php

namespace App\Events;


use App\Entity\FilmLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FilmSubscriber implements EventSubscriberInterface  {

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            FilmAddedEvent::NAME => 'onFilmAdded',
            FilmDeletedEvent::NAME => 'onFilmDeleted'
        ];
    }

    public function onFilmAdded(FilmAddedEvent $event) : void
    {

        $filmTitle = $event->getFilm()->getTitle();

        $filmLog = new FilmLog();
        $filmLog->setFilmTitle($filmTitle);
        $filmLog->setActiontype('add');
        $timestamp = new \DateTime('now');
        $filmLog->setTimestamp($timestamp);

        $this->entityManager->persist($filmLog);
        $this->entityManager->flush();

    }

    public function onFilmDeleted(FilmDeletedEvent $event) : void
    {

        $filmTitle = $event->getFilm()->getTitle();

        $filmLog = new FilmLog();
        $filmLog->setFilmTitle($filmTitle);
        $filmLog->setActiontype('delete');
        $timestamp = new \DateTime('now');
        $filmLog->setTimestamp($timestamp);

        $this->entityManager->persist($filmLog);
        $this->entityManager->flush();

    }

}
