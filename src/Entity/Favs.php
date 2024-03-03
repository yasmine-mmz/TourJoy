<?php

namespace App\Entity;

use App\Repository\FavsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavsRepository::class)]
class Favs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idF')]
    private ?int $idF = null;

    #[ORM\ManyToOne(inversedBy: 'favs')]
    #[ORM\JoinColumn(name:'user',referencedColumnName:'id')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'favs')]
    #[ORM\JoinColumn(name:'acc',referencedColumnName:'refA')]
    private ?Accomodation $acc = null;

    public function getIdF(): ?int
    {
        return $this->idF;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAcc(): ?Accomodation
    {
        return $this->acc;
    }

    public function setAcc(?Accomodation $acc): static
    {
        $this->acc = $acc;

        return $this;
    }
}
