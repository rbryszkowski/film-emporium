<?php

namespace App\Entity;

use App\Repository\FilmLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FilmLogRepository::class)
 */
class FilmLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filmTitle;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="/^add$|^delete$|^update$/")
     */
    private $actionType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate() : ?\DateTime
    {
        return $this->date;
    }


    public function setDate(\DateTime $dateTime): self
    {

        $this->date = $dateTime;

        return $this;
    }

    public function getActionType(): ?string
    {
        return $this->actionType;
    }

    public function setActionType(string $actionType): self
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilmTitle()
    {
        return $this->filmTitle;
    }

    /**
     * @param mixed $filmTitle
     */
    public function setFilmTitle($filmTitle): void
    {
        $this->filmTitle = $filmTitle;
    }

}
