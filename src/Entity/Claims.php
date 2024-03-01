<?php

namespace App\Entity;

use App\Repository\ClaimsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClaimsRepository::class)]
class Claims
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 24)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-zA-Z0-9]+$/')]
    #[Assert\Length(max: 24,min: 3)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255,min: 5)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createDate = null;
 
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'claims')]
    #[Assert\NotBlank]
    private ?Categories $fkC = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $reply = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getFkC(): ?Categories
    {
        return $this->fkC;
    }

    public function setFkC(?Categories $fkC): static
    {
        $this->fkC = $fkC;

        return $this;
    }

    public function getReply(): ?string
    {
        return $this->reply;
    }

    public function setReply(?string $reply): static
    {
        $this->reply = $reply;

        return $this;
    }

   
}
