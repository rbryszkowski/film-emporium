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
        $this->log('add', $filmTitle );

    }

    public function onFilmUpdated(FilmUpdatedEvent $event) : void
    {

        $filmTitle = $event->getFilm()->getTitle();
        $this->log('update', $filmTitle);

    }

    public function onFilmDeleted(FilmDeletedEvent $event) : void
    {

        $filmTitle = $event->getFilm()->getTitle();
        $this->log('delete', $filmTitle);

    }

    private function log(string $eventAction, string $filmTitle) : void
    {

        $filmLog = new FilmLog();
        $filmLog->setFilmTitle($filmTitle);
        $filmLog->setActionType($eventAction);
        $filmLog->setDate(new \DateTime('now'));
        $this->entityManager->persist($filmLog);
        $this->entityManager->flush();

    }

}
