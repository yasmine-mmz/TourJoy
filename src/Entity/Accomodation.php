<?php

namespace App\Entity;

use App\Repository\AccomodationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AccomodationRepository::class)]
#[Vich\Uploadable]
class Accomodation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'refA')]
    private ?int $refA = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"name is required")]
    #[Assert\Length(max: 25)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"type is required")]
    #[Assert\Length(max: 25)]
    private ?string $type = null;

    #[Vich\UploadableField(mapping: 'accomodation_images' , fileNameProperty: 'imageName' )]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string')]
    private ?string $imageName = null;  
    
    #[ORM\Column]
    #[Assert\NotBlank(message:"room number is required")]
    #[Assert\Positive(message:"room number must be positive")]
    private ?int $nbRooms = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"price is required")]
    #[Assert\Positive(message:"the price must be positive")]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'name', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"address is required")]
    private ?string $location = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getRefA(): ?int
    {
        return $this->refA;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !=$imageFile)
         {
            $this->updateAt = new \DateTimeImmutable();
         }
    }
    
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }
    
    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    public function getNbRooms(): ?int
    {
        return $this->nbRooms;
    }

    public function setNbRooms(?int $nbRooms): static
    {
        $this->nbRooms = $nbRooms;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setName($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getName() === $this) {
                $reservation->setName(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }
}
