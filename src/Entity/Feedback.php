<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
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
}
