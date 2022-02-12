<?php

namespace App\Entity;

use App\Repository\PeopleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
class People
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 128)]
    private $name;

    #[ORM\Column(type: 'string', length: 128)]
    private $family;

    #[ORM\Column(type: 'string', length: 128)]
    private $patronymic;

    #[ORM\Column(type: 'date')]
    private $birthday;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 13)]
    private $phone;

    #[ORM\OneToOne(mappedBy: 'people', targetEntity: Employee::class, cascade: ['persist', 'remove'])]
    #[Ignore]
    private $employee;

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

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        // set the owning side of the relation if necessary
        if ($employee->getPeople() !== $this) {
            $employee->setPeople($this);
        }

        $this->employee = $employee;

        return $this;
    }

    /**
     * Получить ассоциативный массив, представляющий данные человека
     * @return array
     */
    #[Ignore]
    public function getAssocData(): array
    {
        $date = $this->getBirthday()->format('Y-m-d');

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'family' => $this->getFamily(),
            'patronymic' => $this->getPatronymic(),
            'birthday' => $date,
            'email' => $this->getEmail(),
            'phone' => $this->getPhone()
        ];
    }
}
