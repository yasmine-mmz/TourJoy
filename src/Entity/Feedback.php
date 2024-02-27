<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message:"Comment is required")]   
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\ManyToOne(inversedBy: 'feedback')]
    #[ORM\JoinColumn(name: 'fk_guide_id', referencedColumnName:'CIN')]
    private ?Guide $fkGuide = null;

    #[ORM\Column(nullable: true)]
    private ?int $useful = null;

    #[ORM\Column(nullable: true)]
    private ?int $not_useful = null;

    #[ORM\ManyToOne(inversedBy: 'feedback')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\OneToMany(mappedBy: 'feedback', targetEntity: Likesystem::class)]
    private Collection $likesystems;

    public function __construct()
    {
        $this->likesystems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getFkGuide(): ?Guide
    {
        return $this->fkGuide;
    }

    public function setFkGuide(?Guide $fkGuide): static
    {
        $this->fkGuide = $fkGuide;

        return $this;
    }

    public function __toString(){
        return $this->comment;
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

    /**
     * @return Collection<int, Likesystem>
     */
    public function getLikesystems(): Collection
    {
        return $this->likesystems;
    }

    public function addLikesystem(Likesystem $likesystem): static
    {
        if (!$this->likesystems->contains($likesystem)) {
            $this->likesystems->add($likesystem);
            $likesystem->setFeedback($this);
        }

        return $this;
    }

    public function removeLikesystem(Likesystem $likesystem): static
    {
        if ($this->likesystems->removeElement($likesystem)) {
            // set the owning side to null (unless already changed)
            if ($likesystem->getFeedback() === $this) {
                $likesystem->setFeedback(null);
            }
        }

        return $this;
    }

    
}
