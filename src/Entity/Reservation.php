<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idR')]
    private ?int $idR = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan("today", message:"Start date must be after the present day")]
    #[Assert\NotBlank(message:"Start Date is required")]
    private ?\DateTimeInterface $startDate = null;

 
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(propertyPath:"startDate", message:"End date must be after the start date")]
    #[Assert\NotBlank(message:"End Date is required")]
    
    private ?\DateTimeInterface $endDate = null;
    
    #CaptchaAssert\ValidCaptcha(  message : "CAPTCHA validation failed, try again." )
    protected $captchaCode;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(name:'name',referencedColumnName:'refA')]
    private ?Accomodation $name = null;

    public function getIdR(): ?int
    {
        return $this->idR;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getName(): ?Accomodation
    {
        return $this->name;
    }

    public function setName(?Accomodation $name): static
    {
        $this->name = $name;

        return $this;
    }
    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }
}
