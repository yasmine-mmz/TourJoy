<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'There is already a category with this name')]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16,nullable: true,unique:true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-zA-Z]+$/')]
    #[Assert\Length(max: 16,min: 3)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'fkC', targetEntity: Claims::class)]
    private Collection $claims;

    public function __construct()
    {
        $this->claims = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Claims>
     */
    public function getClaims(): Collection
    {
        return $this->claims;
    }

    public function addClaim(Claims $claim): static
    {
        if (!$this->claims->contains($claim)) {
            $this->claims->add($claim);
            $claim->setFkC($this);
        }

        return $this;
    }

    public function removeClaim(Claims $claim): static
    {
        if ($this->claims->removeElement($claim)) {
            // set the owning side to null (unless already changed)
            if ($claim->getFkC() === $this) {
                $claim->setFkC(null);
            }
        }

        return $this;
    }
}
