<?php

namespace App\Entity;

use App\Repository\QRRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: QRRepository::class)]
class QR
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    #[ORM\Column(type: 'boolean')]
    private $is_expired;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getIsExpired(): ?bool
    {
        return $this->is_expired;
    }

    public function setIsExpired(bool $is_expired): self
    {
        $this->is_expired = $is_expired;

        return $this;
    }

    #[Pure] public function isMyToken(string $token): bool
    {
        return $this->getToken() === $token;
    }
}
