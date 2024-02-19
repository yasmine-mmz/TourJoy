<?php

namespace App\Entity;

use App\Repository\MonumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: MonumentRepository::class)]
#[Vich\Uploadable]
class Monument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ref = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Name is required !")]
    private ?string $nameM = null;

    #[Vich\UploadableField(mapping: 'monuments_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

   #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"price is required !")]
    #[Assert\Positive(message: "Price must be a positive number")]
    private ?int $entryprice = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    // #[ORM\Column(type: Types::BLOB)]
    // private $image = null;

    #[ORM\ManyToOne(inversedBy: 'monuments')]
    private ?Country $fkcountry = null;

  //  #[ORM\Column(type="datetime")]
    private ?\DateTimeInterface $updatedAt;
    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: "Latitude is required!")]
    #[Assert\Type(type: 'numeric', message: "Latitude must be a numeric value!")]
    private ?float $latitude = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: "Longitude is required!")]
    #[Assert\Type(type: 'numeric', message: "Longitude must be a numeric value!")]
    private ?float $longitude = null;

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function getNameM(): ?string
    {
        return $this->nameM;
    }

    public function setNameM(string $nameM): self
    {
        $this->nameM = $nameM;

        return $this;
    }
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getEntryprice(): ?int
    {
        return $this->entryprice;
    }

    public function setEntryprice(int $entryprice): static
    {
        $this->entryprice = $entryprice;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    // public function getImage()
    // {
    //     return $this->image;
    // }

    // public function setImage($image): static
    // {
    //     $this->image = $image;

    //     return $this;
    // }

    public function getFkcountry(): ?Country
    {
        return $this->fkcountry;
    }

    public function setFkcountry(?Country $fkcountry): static
    {
        $this->fkcountry = $fkcountry;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
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
    public function __toString(){
        return $this->nameM;
    }
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
