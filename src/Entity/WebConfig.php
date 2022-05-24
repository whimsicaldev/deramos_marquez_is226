<?php

namespace App\Entity;

use App\Repository\WebConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebConfigRepository::class)]
class WebConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $termsAndConditions;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTermsAndConditions(): ?string
    {
        return $this->termsAndConditions;
    }

    public function setTermsAndConditions(?string $termsAndConditions): self
    {
        $this->termsAndConditions = $termsAndConditions;

        return $this;
    }
}
