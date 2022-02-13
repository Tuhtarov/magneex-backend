<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'user', targetEntity: Employee::class, cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private Employee $employee;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    private $login;

    #[ORM\Column(type: 'string', length: 255)]
    #[Ignore]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $activated;

    #[Ignore]
    private $plainPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

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

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        try {
            // берём роль через Employee
            $currentRole = $this->employee->getRole()->getName();
            $currentRole = 'ROLE_' . strtoupper($currentRole);
            $roles[] = $currentRole;
        } catch (Exception) {
        }

        return array_unique($roles);
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    #[Ignore]
    public function getSalt(): ?string
    {
        return null;
    }

    #[Ignore]
    public function getUsername(): string
    {
        return (string)$this->login;
    }

    #[Ignore]
    public function getUserIdentifier(): string
    {
        return (string)$this->login;
    }
}
