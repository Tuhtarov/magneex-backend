<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use JetBrains\PhpStorm\Pure;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'user', targetEntity: Employee::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Employee $employee;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    private $login;

    #[ORM\Column(type: 'string', length: 255)]
    #[Exclude]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $activated;

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

    #[Pure]
    public function getRoles(): array
    {
        $roles = [];

        // берём роль пользователя через Employee (связана с User)
        try {
            $currentRole = $this->employee->getRole()->getName();
        } catch (Exception $e) {
            $currentRole = null;
        }

        if (!empty($currentRole)) {
            $currentRole = 'ROLE_' . strtoupper($currentRole);
            $roles[] = $currentRole;
        }

        // роли в симфони начинаются с ROLE_ (нерушимая конвенция)
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    #[Pure]
    public function isAdmin(): bool
    {
        $currentRoles = $this->getRoles();

        foreach ($currentRoles as $currentRole) {
            if ($currentRole === 'ROLE_ADMIN') {
                return true;
            }
        }

        return false;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return (string) $this->login;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }
}
