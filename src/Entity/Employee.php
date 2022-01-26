<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'employee', targetEntity: People::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $people;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'employees')]
    private $role;

    #[ORM\ManyToMany(targetEntity: Visit::class, mappedBy: 'employee')]
    private $visits;

    #[ORM\OneToOne(mappedBy: 'employee', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: JobPosition::class, inversedBy: 'employees')]
    private $jobPosition;

    public const JOB_POSITION_FK_NAME = 'job_position_id';
    public const ROLE_FK_NAME = 'role_id';

    public function __construct()
    {
        $this->visits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeople(): ?People
    {
        return $this->people;
    }

    public function setPeople(People $people): self
    {
        $this->people = $people;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Visit[]
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Visit $visit): self
    {
        if (!$this->visits->contains($visit)) {
            $this->visits[] = $visit;
            $visit->addEmployee($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->removeElement($visit)) {
            $visit->removeEmployee($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        // set the owning side of the relation if necessary
        if ($user->getEmployee() !== $this) {
            $user->setEmployee($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getJobPosition(): ?JobPosition
    {
        return $this->jobPosition;
    }

    public function setJobPosition(?JobPosition $jobPosition): self
    {
        $this->jobPosition = $jobPosition;

        return $this;
    }
}
