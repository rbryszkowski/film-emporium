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
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="/^add$|^delete$|^update$/")
     */
    private $actiontype;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp() : ?\DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getActiontype(): ?string
    {
        return $this->actiontype;
    }

    public function setActiontype(string $actiontype): self
    {
        $this->actiontype = $actiontype;

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
