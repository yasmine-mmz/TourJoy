<?php

namespace App\Entity;

use App\Repository\ClaimsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClaimsRepository::class)]
class Claims
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createDate = null;

    #[ORM\Column]
    private ?int $state = null;

    #[ORM\ManyToOne(inversedBy: 'claims')]
    private ?Categories $fkC = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): static
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
}
