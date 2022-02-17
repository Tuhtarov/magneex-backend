<?php

namespace App\Entity;

use App\Repository\JobPositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JobPositionRepository::class)]
class JobPosition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'jobPosition', targetEntity: Employee::class)]
    #[Ignore]
    #[Assert\DisableAutoMapping]
    private $employees;

    #[ORM\Column(type: 'time')]
    private $beginWorkTime;

    #[ORM\Column(type: 'time')]
    private $endWorkTime;

    #[ORM\Column(type: 'integer')]
    private $salary;

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

    public function getBeginWorkTime(): ?string
    {
        if ($this->beginWorkTime) {
            return $this->beginWorkTime->format('H:i');
        }
        return null;
    }

    public function setBeginWorkTime(\DateTimeInterface $beginWorkTime): self
    {
        $this->beginWorkTime = $beginWorkTime;

        return $this;
    }

    public function getEndWorkTime(): ?string
    {
        if ($this->endWorkTime) {
            return $this->endWorkTime->format('H:i');
        }
        return null;
    }

    public function setEndWorkTime(\DateTimeInterface $endWorkTime): self
    {
        $this->endWorkTime = $endWorkTime;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }
}
