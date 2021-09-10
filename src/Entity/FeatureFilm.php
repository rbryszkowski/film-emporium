<?php

namespace App\Entity;

use App\Repository\FeatureFilmRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass=FeatureFilmRepository::class)
 */
class FeatureFilm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=255)
     * @OneToOne(targetEntity="Film", mappedBy = "id")
     */
    private $filmId;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilmId(): ?int
    {
        return $this->filmId;
    }

    public function setFilmId(int $filmId): self
    {
        $this->filmId = $filmId;

        return $this;
    }

}
