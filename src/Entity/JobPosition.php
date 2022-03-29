<?php

namespace App\Entity;

use App\Repository\JobPositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $beginWork;

    #[ORM\Column(type: 'time')]
    private $endWork;

    #[ORM\Column(type: 'integer')]
    private $salary;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

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


    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getBeginWork(): ?\DateTimeInterface
    {
        return $this->beginWork;
    }

    public function setBeginWork(\DateTimeInterface $beginWork): self
    {
        $this->beginWork = $beginWork;

        return $this;
    }

    public function getEndWork(): ?\DateTimeInterface
    {
        return $this->endWork;
    }

    public function setEndWork(\DateTimeInterface $endWork): self
    {
        $this->endWork = $endWork;

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setJobPosition($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getJobPosition() === $this) {
                $employee->setJobPosition(null);
            }
        }

        return $this;
    }
}
