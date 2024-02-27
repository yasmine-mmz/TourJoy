<?php

namespace App\Entity;

use App\Repository\LikesystemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikesystemRepository::class)]
class Likesystem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $useful = null;

    #[ORM\Column(nullable: true)]
    private ?int $not_useful = null;

    #[ORM\ManyToOne(inversedBy: 'likesystems')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'likesystems')]
    private ?Feedback $feedback = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUseful(): ?int
    {
        return $this->useful;
    }

    public function setUseful(?int $useful): static
    {
        $this->useful = $useful;

        return $this;
    }

    public function getNotUseful(): ?int
    {
        return $this->not_useful;
    }

    public function setNotUseful(?int $not_useful): static
    {
        $this->not_useful = $not_useful;

        return $this;
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

    public function getFeedback(): ?Feedback
    {
        return $this->feedback;
    }

    public function setFeedback(?Feedback $feedback): static
    {
        $this->feedback = $feedback;

        return $this;
    }
}
