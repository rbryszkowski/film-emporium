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
            FilmDeletedEvent::NAME => 'onFilmDeleted',
            FilmUpdatedEvent::NAME => 'onFilmUpdated'
        ];
    }

    public function onFilmAdded(FilmAddedEvent $event) : void
    {

        $filmTitle = $event->getFilm()->getTitle();
        $this->addFilmLog('add', $filmTitle );

    }

    public function onFilmUpdated(FilmUpdatedEvent $event) : void
    {

        $filmTitle = $event->getFilm()->getTitle();
        $this->addFilmLog('update', $filmTitle);

    }

    public function onFilmDeleted(FilmDeletedEvent $event) : void
    {

        $filmTitle = $event->getFilm()->getTitle();
        $this->addFilmLog('delete', $filmTitle);

    }

    private function addFilmLog(string $eventAction, string $filmTitle) {

        $filmLog = new FilmLog();
        $filmLog->setFilmTitle($filmTitle);
        $filmLog->setActiontype($eventAction);
        $timestamp = new \DateTime('now');
        $filmLog->setTimestamp($timestamp);
        $this->entityManager->persist($filmLog);
        $this->entityManager->flush();

    }

}
