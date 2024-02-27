<?php

namespace App\Entity;

use App\Repository\GuideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: GuideRepository::class)]
#[Vich\Uploadable]
/**
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"CIN"},
 *     message="This CIN is already in use."
 * )
 */

class Guide
{

    
    #[ORM\Id]
    #[ORM\Column(name: 'CIN', unique:true)]
    #[Assert\NotBlank(message:"CIN is required")]   
   #[Assert\Length( min:8,max: 8)]
    private ?int $CIN = null;

    #[Vich\UploadableField(mapping: 'guide_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

   #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    private ?\DateTimeInterface $updatedAt;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"first name is required")]
    private ?string $firstnameG = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"last name is required")]
    private ?string $lastnameG = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Email is required")]
    #[Assert\Email(message:"The email is not a valid email")]
    private ?string $emailaddressG = null;

    #[ORM\Column(length: 255)]
    private ?string $phonenumberG = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"country is required")]
    private ?string $countryG = null;

    #[ORM\Column(length: 255)]
    private ?string $genderG = null;

    #[ORM\OneToMany(mappedBy: 'fkGuide', targetEntity: Feedback::class)]
    private Collection $feedback;

    #[ORM\OneToMany(mappedBy: 'guide_id', targetEntity: Booking::class)]
    private Collection $date;

    #[ORM\OneToMany(mappedBy: 'guide_id', targetEntity: Booking::class)]
    private Collection $bookings;

    #[ORM\Column(length: 255)]
    private ?string $language = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dob = null;

    public function __construct()
    {
        $this->feedback = new ArrayCollection();
        $this->date = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getCIN(): ?int
    {
        return $this->CIN;
    }

    public function getFirstnameG(): ?string
    {
        return $this->firstnameG;
    }

    public function setFirstnameG(?string $firstnameG): static
    {
        $this->firstnameG = $firstnameG;

        return $this;
    }

    public function getLastnameG(): ?string
    {
        return $this->lastnameG;
    }
    public function setCIN(?string $CIN): static
    {
        $this->CIN = $CIN;

        return $this;
    }
    public function setLastnameG(?string $lastnameG): static
    {
        $this->lastnameG = $lastnameG;

        return $this;
    }

    public function getEmailaddressG(): ?string
    {
        return $this->emailaddressG;
    }

    public function setEmailaddressG(?string $emailaddressG): static
    {
        $this->emailaddressG = $emailaddressG;

        return $this;
    }

    public function getPhonenumberG(): ?string
    {
        return $this->phonenumberG;
    }

    public function setPhonenumberG(?string $phonenumberG): static
    {
        $this->phonenumberG = $phonenumberG;

        return $this;
    }

    public function getCountryG(): ?string
    {
        return $this->countryG;
    }

    public function setCountryG(?string $countryG): static
    {
        $this->countryG = $countryG;

        return $this;
    }

    public function getGenderG(): ?string
    {
        return $this->genderG;
    }

    public function setGenderG(string $genderG): static
    {
        $this->genderG = $genderG;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setFkGuide($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
       if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getFkGuide() === $this) {
                $feedback->setFkGuide(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->CIN;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getDate(): Collection
    {
        return $this->date;
    }

    public function addDate(Booking $date): static
    {
        if (!$this->date->contains($date)) {
            $this->date->add($date);
            $date->setGuideId($this);
        }

        return $this;
    }

    public function removeDate(Booking $date): static
    {
        if ($this->date->removeElement($date)) {
            // set the owning side to null (unless already changed)
            if ($date->getGuideId() === $this) {
                $date->setGuideId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setGuideId($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getGuideId() === $this) {
                $booking->setGuideId(null);
            }
        }

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

        return $this;
    }
}
