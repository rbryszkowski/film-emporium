<?php

namespace App\Events;

use App\Entity\Film;

interface FilmChangeEventInterface {

    public function getFilm(): Film;

}
