<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: ['username', 'email'],
    errorPath: 'email',
    message: 'There is already an account with this email.'
    )]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nickname;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $password;

    #[ORM\Column(type: 'string', length: 255, unique: true, updatable: false)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'boolean')]
    private $isEmailVerificationSent = false;

    #[ORM\Column(type: 'boolean')]
    private $isDisabled = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateDisabled;

    private $password1;
    private $password2;
    private $displayName;
    private $adjustmentAmount;
    private $isBorrower;
    private $isPeer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword1(): ?string
    {
        return $this->password1;
    }

    public function setPassword1(string $password1): self
    {
        $this->password1 = $password1;

        return $this;
    }

    public function getPassword2(): ?string
    {
        return $this->password2;
    }

    public function setPassword2(string $password2): self
    {
        $this->password2 = $password2;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        
        if(empty($roles))
            $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isEmailVerificationSent(): ?bool
    {
        return $this->isEmailVerificationSent;
    }

    public function setIsEmailVerificationSent(bool $isEmailVerificationSent): self
    {
        $this->isEmailVerificationSent = $isEmailVerificationSent;

        return $this;
    }

    public function getIsDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    public function getDateDisabled(): ?\DateTimeInterface
    {
        return $this->dateDisabled;
    }

    public function setDateDisabled(?\DateTimeInterface $dateDisabled): self
    {
        $this->dateDisabled = $dateDisabled;

        return $this;
    }

    public function equals(User $user): bool
    {
        return $this->id != null && $user->getId() != null && $this->id == $user->getId();
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getAdjustmentAmount(): ?string
    {
        return $this->adjustmentAmount;
    }

    public function setAdjustmentAmount(string $adjustmentAmount): self
    {
        $this->adjustmentAmount = $adjustmentAmount;

        return $this;
    }

    public function getIsBorrower(): ?bool
    {
        return $this->isBorrower;
    }

    public function setIsBorrower(bool $isBorrower): self
    {
        $this->isBorrower = $isBorrower;

        return $this;
    }

    public function getIsPeer(): ?bool
    {
        return $this->isPeer;
    }

    public function setIsPeer(bool $isPeer): self
    {
        $this->isPeer = $isPeer;

        return $this;
    }
}