<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: CountryRepository::class)]
/**
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"name"},
 *     message="This Country is already added."
 * )
 */
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'name', unique: true, length: 255)] 
    #[Assert\NotBlank(message:"Country name is required !")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\OneToMany(mappedBy: 'fkcountry', targetEntity: Monument::class)]
    private Collection $monuments;

    #[ORM\OneToMany(mappedBy: 'fkpays', targetEntity: Accomodation::class)]
    private Collection $accomodations;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Guide::class)]
    private Collection $guides;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Transport::class)]
    private Collection $transports;

    public function __construct()
    {
        $this->monuments = new ArrayCollection();
        $this->accomodations = new ArrayCollection();
        $this->guides = new ArrayCollection();
        $this->transports = new ArrayCollection();
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

    /**
     * @return Collection<int, Accomodation>
     */
    public function getAccomodations(): Collection
    {
        return $this->accomodations;
    }

    public function addAccomodation(Accomodation $accomodation): static
    {
        if (!$this->accomodations->contains($accomodation)) {
            $this->accomodations->add($accomodation);
            $accomodation->setFkpays($this);
        }

        return $this;
    }

    public function removeAccomodation(Accomodation $accomodation): static
    {
        if ($this->accomodations->removeElement($accomodation)) {
            // set the owning side to null (unless already changed)
            if ($accomodation->getFkpays() === $this) {
                $accomodation->setFkpays(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Guide>
     */
    public function getGuides(): Collection
    {
        return $this->guides;
    }

    public function addGuide(Guide $guide): static
    {
        if (!$this->guides->contains($guide)) {
            $this->guides->add($guide);
            $guide->setCountry($this);
        }

        return $this;
    }

    public function removeGuide(Guide $guide): static
    {
        if ($this->guides->removeElement($guide)) {
            // set the owning side to null (unless already changed)
            if ($guide->getCountry() === $this) {
                $guide->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transport>
     */
    public function getTransports(): Collection
    {
        return $this->transports;
    }

    public function addTransport(Transport $transport): static
    {
        if (!$this->transports->contains($transport)) {
            $this->transports->add($transport);
            $transport->setCountry($this);
        }

        return $this;
    }

    public function removeTransport(Transport $transport): static
    {
        if ($this->transports->removeElement($transport)) {
            // set the owning side to null (unless already changed)
            if ($transport->getCountry() === $this) {
                $transport->setCountry(null);
            }
        }

        return $this;
    }

}
