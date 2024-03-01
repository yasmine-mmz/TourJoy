<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[UniqueEntity(fields: ['plan'], message: 'There is already a subscription with this name')]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-zA-Z]+$/')]
    #[Assert\Length(max: 255)]
    private ?string $plan = null;

    #[ORM\Column]
    #[Assert\LessThanOrEqual(value: 356)]
    #[Assert\NotBlank]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    private ?Transport $typeT = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlan(): ?string
    {
        return $this->plan;
    }

    public function setPlan(?string $plan): static
    {
        $this->plan = $plan;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getTypeT(): ?Transport
    {
        return $this->typeT;
    }

    public function setTypeT(?Transport $typeT): static
    {
        $this->typeT = $typeT;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
