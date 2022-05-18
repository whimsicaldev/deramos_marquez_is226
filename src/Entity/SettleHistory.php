<?php

namespace App\Entity;

use App\Repository\SettleHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettleHistoryRepository::class)]
class SettleHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Connection::class, inversedBy: 'date')]
    #[ORM\JoinColumn(nullable: false)]
    private $connection;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConnection(): ?Connection
    {
        return $this->connection;
    }

    public function setConnection(?Connection $connection): self
    {
        $this->connection = $connection;

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
}
