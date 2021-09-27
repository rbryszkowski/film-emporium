<?php

namespace App\Events;

use App\Entity\Film;
use Symfony\Contracts\EventDispatcher\Event;

class FilmAddedEvent extends Event {

    public const NAME = 'film.added';

    protected $film;

    public function __construct(Film $film)
    {
        $this->film = $film;
    }

    public function getFilm(): Film
    {
        return $this->film;
    }

}
