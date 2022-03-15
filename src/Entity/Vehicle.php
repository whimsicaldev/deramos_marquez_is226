<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'float')]
    private $default_min_fair;

    #[ORM\Column(type: 'float')]
    private $default_fair_inc_per_km;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDefaultMinFair(): ?float
    {
        return $this->default_min_fair;
    }

    public function setDefaultMinFair(float $default_min_fair): self
    {
        $this->default_min_fair = $default_min_fair;

        return $this;
    }

    public function getDefaultFairIncPerKm(): ?float
    {
        return $this->default_fair_inc_per_km;
    }

    public function setDefaultFairIncPerKm(float $default_fair_inc_per_km): self
    {
        $this->default_fair_inc_per_km = $default_fair_inc_per_km;

        return $this;
    }
}
