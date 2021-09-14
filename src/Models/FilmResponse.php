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

        $this->type = $responseBody['Type'];
        $this->title = $responseBody['Title'];
        $this->genre = $responseBody['Genre'];
        $this->director = $responseBody['Director'];
        $this->writer = $responseBody['Writer'];
        $this->plot = $responseBody['Plot'];
        $this->rated = $responseBody['Rated'];
        $this->year = $responseBody['Year'];
        $this->runtime = $responseBody['Runtime'];
        $this->ratings = $responseBody['Ratings'];
        $this->poster = $responseBody['Poster'];

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


