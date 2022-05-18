<?php

namespace App\Entity;

use App\Repository\ConnectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConnectionRepository::class)]
class Connection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "email")]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "email")]
    #[ORM\JoinColumn(nullable: false)]
    private $peer;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $amount = 0;

    #[ORM\Column(type: 'enumconnection')]
    private $status;

    private $peerEmail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPeer(): ?User
    {
        return $this->peer;
    }

    public function setPeer(?User $peer): self
    {
        $this->peer = $peer;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPeerEmail(): ?string
    {
        return $this->peerEmail;
    }

    public function setPeerEmail($peerEmail): self
    {
        $this->peerEmail = $peerEmail;

        return $this;
    }
}
