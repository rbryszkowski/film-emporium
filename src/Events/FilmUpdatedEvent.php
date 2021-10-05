<?php

namespace App\Events;

use App\Entity\Film;
use Symfony\Contracts\EventDispatcher\Event;

class FilmUpdatedEvent extends Event implements FilmChangeEventInterface
{

    public const NAME = 'film.updated';

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
