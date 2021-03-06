<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 * @UniqueEntity("title")
 */
class Film
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=9, unique=true, nullable=true)
     */
    private $imdbID;

    /**
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="films", cascade={"persist"})
     */
    private $genres;

    /**
     * Many films have one director. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Director", inversedBy="films", cascade={"persist"})
     */
    private $director;

    /**
     * @OneToOne(targetEntity="FeatureFilm", inversedBy = "filmId")
     */
    private $featureFilm;

    public function getDirector(): ?Director
    {
        return $this->director;
    }

    public function setDirector(?Director $director): self
    {
        $this->director = $director;

        return $this;
    }



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=200)
     * @Assert\Regex(pattern="/[A-Za-z]+/")
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * @param mixed $genres
     *
     * @return Film
     */
    public function setGenres($genres)
    {
        $this->genres = $genres;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImdbID()
    {
        return $this->imdbID;
    }

    /**
     * @param mixed $imdbID
     */
    public function setImdbID($imdbID): void
    {
        $this->imdbID = $imdbID;
    }


}
