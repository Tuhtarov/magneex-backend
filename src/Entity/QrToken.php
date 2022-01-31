<?php

namespace App\Entity;

use App\Repository\QrTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QrTokenRepository::class)]
class QrToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    #[ORM\OneToOne(mappedBy: 'qrToken', targetEntity: Visit::class, cascade: ['persist', 'remove'])]
    private $visit;

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

    public function getVisit(): ?Visit
    {
        return $this->visit;
    }

    public function setVisit(?Visit $visit): self
    {
        // unset the owning side of the relation if necessary
        if ($visit === null && $this->visit !== null) {
            $this->visit->setQrToken(null);
        }

        // set the owning side of the relation if necessary
        if ($visit !== null && $visit->getQrToken() !== $this) {
            $visit->setQrToken($this);
        }

        $this->visit = $visit;

        return $this;
    }
}
