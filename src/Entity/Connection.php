<?php

namespace App\Entity;

use App\Repository\ConnectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $userDebt = 0;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $peerDebt = 0;

    #[ORM\OneToMany(mappedBy: 'connection', targetEntity: SettleHistory::class, orphanRemoval: true)]
    private $settleHistoryList;

    public function __construct()
    {
        $this->settleHistoryList = new ArrayCollection();
    }

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

    public function getUserDebt(): ?string
    {
        return $this->userDebt;
    }

    public function setUserDebt(string $userDebt): self
    {
        $this->userDebt = $userDebt;

        return $this;
    }

    public function getPeerDebt(): ?string
    {
        return $this->peerDebt;
    }

    public function setPeerDebt(string $peerDebt): self
    {
        $this->peerDebt = $peerDebt;

        return $this;
    }

    /**
     * @return Collection<int, SettleHistory>
     */
    public function getSettleHistoryList(): Collection
    {
        return $this->settleHistoryList;
    }

    public function addSettleHistory(SettleHistory $settleHistory): self
    {
        if (!$this->settleHistoryList->contains($settleHistory)) {
            $this->settleHistoryList[] = $settleHistory;
            $settleHistory->setConnection($this);
        }

        return $this;
    }

    public function removeSettleHistory(SettleHistory $settleHistory): self
    {
        if ($this->settleHistoryList->removeElement($settleHistory)) {
            // set the owning side to null (unless already changed)
            if ($settleHistory->getConnection() === $this) {
                $settleHistory->setConnection(null);
            }
        }
        return $this;
    }
}