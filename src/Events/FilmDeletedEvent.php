<?php

namespace App\Events;

use App\Entity\Film;
use Symfony\Contracts\EventDispatcher\Event;

class FilmDeletedEvent extends Event
{

    public const NAME = 'film.deleted';

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
