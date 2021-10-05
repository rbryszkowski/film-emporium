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
            FilmAddedEvent::NAME => 'handleEvent',
            FilmDeletedEvent::NAME => 'handleEvent',
            FilmUpdatedEvent::NAME => 'handleEvent'
        ];

    }

    public function handleEvent(FilmChangeEventInterface $event) {

        $filmTitle = $event->getFilm()->getTitle();

        if ($event instanceof FilmAddedEvent) {
            $this->log('add', $filmTitle );
        } else if ($event instanceof FilmDeletedEvent ){
            $this->log('delete', $filmTitle);
        } else if ($event instanceof FilmUpdatedEvent) {
            $this->log('update', $filmTitle);
        }

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
