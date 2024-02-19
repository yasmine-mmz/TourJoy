<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Country name is required !")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\OneToMany(mappedBy: 'fkcountry', targetEntity: Monument::class)]
    private Collection $monuments;

    public function __construct()
    {
        $this->monuments = new ArrayCollection();
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

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, Monument>
     */
    public function getMonuments(): Collection
    {
        return $this->monuments;
    }

    public function addMonument(Monument $monument): static
    {
        if (!$this->monuments->contains($monument)) {
            $this->monuments->add($monument);
            $monument->setFkcountry($this);
        }

        return $this;
    }

    public function removeMonument(Monument $monument): static
    {
        if ($this->monuments->removeElement($monument)) {
            // set the owning side to null (unless already changed)
            if ($monument->getFkcountry() === $this) {
                $monument->setFkcountry(null);
            }
        }

        return $this;
    }
}
