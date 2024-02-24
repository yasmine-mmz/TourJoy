<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"date"},
 *     message="This date is already in use."
 * )
 */

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(name: 'guide_id', referencedColumnName:'CIN')]
    private ?Guide $guide_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, unique:true)]
    #[Assert\NotBlank(message:"date is required")]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuideId(): ?Guide
    {
        return $this->guide_id;
    }

    public function setGuideId(?Guide $guide_id): static
    {
        $this->guide_id = $guide_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
