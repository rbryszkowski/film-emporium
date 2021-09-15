<?php

namespace App\Models;

class FilmResponse
{

    private $title;
    private $type;
    private $genre;
    private $director;
    private $writer;
    private $plot;
    private $rated;
    private $year;
    private $runtime;
    private $ratings;
    private $poster;

    public function __construct($responseBody) {

        // these need wrapping otherwise the test will fail on every single one.
        // or update your test to include all of these properties.

        $this->type = $responseBody['Type'] ?? null;
        $this->title = $responseBody['Title'] ?? null;
        $this->genre = $responseBody['Genre'] ?? null;
        $this->director = $responseBody['Director'] ?? null;
        $this->writer = $responseBody['Writer'] ?? null;
        $this->plot = $responseBody['Plot'] ?? null;
        $this->rated = $responseBody['Rated'] ?? null;
        $this->year = $responseBody['Year'] ?? null;
        $this->runtime = $responseBody['Runtime'] ?? null;
        $this->ratings = $responseBody['Ratings'] ?? null;
        $this->poster = $responseBody['Poster'] ?? null;

    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @return mixed
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * @return mixed
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @return mixed
     */
    public function getPlot()
    {
        return $this->plot;
    }

    /**
     * @return mixed
     */
    public function getRated()
    {
        return $this->rated;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * @return mixed
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @return mixed
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

}


