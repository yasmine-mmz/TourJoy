<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: TransportRepository::class)]
#[UniqueEntity(fields: ['typeT'], message: 'There is already a transport with this name')]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255,unique:true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-zA-Z]+$/')]
    #[Assert\Length(max: 255)]
    private ?string $typeT = null;

    #[ORM\OneToMany(mappedBy: 'typeT', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    #[ORM\ManyToOne(inversedBy: 'transports')]
    private ?Country $country = null;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function __toString(): string
{
    return $this->typeT;
}


    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeT(): ?string
    {
        return $this->typeT;
    }

    public function setTypeT(?string $typeT): static
    {
        $this->typeT = $typeT;

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): static
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setTypeT($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): static
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getTypeT() === $this) {
                $subscription->setTypeT(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }
}
