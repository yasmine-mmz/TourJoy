<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
// use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{    

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 25, nullable : true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-zA-Z\-]+$/')]
    #[Assert\Length(max: 25)]
    private ?string $firstName = null;

    #[ORM\Column(length: 25, nullable : true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-zA-Z\-]+$/')]
    #[Assert\Length(max: 25)]
    private ?string $lastName = null;

    #[ORM\Column(length: 8, nullable : true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^\d{8}$/')]
    #[Assert\Length(max: 8)]
    private ?int $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $googleAuthenticatorSecret;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isBanned = false;

    #[ORM\Column(type:'string', nullable:true)]
    private ?string $googleId;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Feedback::class)]
    private Collection $feedback;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Likesystem::class)]
    private Collection $likesystems;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Booking::class)]
    private Collection $bookings;

    #[ORM\OneToMany(mappedBy: 'fkuser', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->feedback = new ArrayCollection();
        $this->likesystems = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }


    // #[ORM\Column(type: 'boolean')]
    // private $is2faEnabled = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    public function isGoogleAuthenticatorEnabled(): bool
{
    return isset($this->googleAuthenticatorSecret) && null !== $this->googleAuthenticatorSecret;
}


   public function getGoogleAuthenticatorUsername(): string
   {
       return $this->email;
   }

   public function getGoogleAuthenticatorSecret(): ?string
   {
       return $this->googleAuthenticatorSecret;
   }

   public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
   {
       $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
   }

   public function getModifiedAt(): ?\DateTimeImmutable
   {
       return $this->modifiedAt;
   }

   public function setModifiedAt(?\DateTimeImmutable $modifiedAt): static
   {
       $this->modifiedAt = $modifiedAt;

       return $this;
   }

   public function isVerified(): bool
   {
       return $this->isVerified;
   }

   public function setIsVerified(bool $isVerified): static
   {
       $this->isVerified = $isVerified;

       return $this;
   }

   public function getIsBanned(): bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;
        return $this;
    }

   public function getGoogleId(): bool
    {
        return $this->googleId;
    }

    public function setGoogleId(bool $googleId): self
    {
        $this->googleId = $googleId;
        return $this;
    }


//    public function is2faEnabled(): bool
//    {
//        return $this->is2faEnabled;
//    }

//    public function setIs2faEnabled(bool $is2faEnabled): self
//    {
//        $this->is2faEnabled = $is2faEnabled;

//        return $this;
//    }

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
        $feedback->setUserId($this);
    }

    return $this;
}

public function removeFeedback(Feedback $feedback): static
{
    if ($this->feedback->removeElement($feedback)) {
        // set the owning side to null (unless already changed)
        if ($feedback->getUserId() === $this) {
            $feedback->setUserId(null);
        }
    }

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
        $likesystem->setUser($this);
    }

    return $this;
}

public function removeLikesystem(Likesystem $likesystem): static
{
    if ($this->likesystems->removeElement($likesystem)) {
        // set the owning side to null (unless already changed)
        if ($likesystem->getUser() === $this) {
            $likesystem->setUser(null);
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
        $booking->setUser($this);
    }

    return $this;
}

public function removeBooking(Booking $booking): static
{
    if ($this->bookings->removeElement($booking)) {
        // set the owning side to null (unless already changed)
        if ($booking->getUser() === $this) {
            $booking->setUser(null);
        }
    }

    return $this;
}

/**
 * @return Collection<int, Reservation>
 */
public function getReservations(): Collection
{
    return $this->reservations;
}

public function addReservation(Reservation $reservation): static
{
    if (!$this->reservations->contains($reservation)) {
        $this->reservations->add($reservation);
        $reservation->setFkuser($this);
    }

    return $this;
}

public function removeReservation(Reservation $reservation): static
{
    if ($this->reservations->removeElement($reservation)) {
        // set the owning side to null (unless already changed)
        if ($reservation->getFkuser() === $this) {
            $reservation->setFkuser(null);
        }
    }

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
        $subscription->setUserId($this);
    }

    return $this;
}

public function removeSubscription(Subscription $subscription): static
{
    if ($this->subscriptions->removeElement($subscription)) {
        // set the owning side to null (unless already changed)
        if ($subscription->getUserId() === $this) {
            $subscription->setUserId(null);
        }
    }

    return $this;
}


}