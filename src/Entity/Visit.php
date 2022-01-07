<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitRepository::class)]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'visits')]
    private $employee;

    #[ORM\OneToOne(inversedBy: 'visit', targetEntity: QrToken::class, cascade: ['persist', 'remove'])]
    private $qrToken;

    #[ORM\Column(type: 'datetime')]
    private $dateBeginWork;

    #[ORM\Column(type: 'datetime')]
    private $dateEndWork;

    public function __construct()
    {
        $this->employee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Employee[]
     */
    public function getEmployee(): Collection
    {
        return $this->employee;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employee->contains($employee)) {
            $this->employee[] = $employee;
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        $this->employee->removeElement($employee);

        return $this;
    }

    public function getQrToken(): ?QrToken
    {
        return $this->qrToken;
    }

    public function setQrToken(?QrToken $qrToken): self
    {
        $this->qrToken = $qrToken;

        return $this;
    }

    public function getDateBeginWork(): ?\DateTimeInterface
    {
        return $this->dateBeginWork;
    }

    public function setDateBeginWork(\DateTimeInterface $dateBeginWork): self
    {
        $this->dateBeginWork = $dateBeginWork;

        return $this;
    }

    public function getDateEndWork(): ?\DateTimeInterface
    {
        return $this->dateEndWork;
    }

    public function setDateEndWork(\DateTimeInterface $dateEndWork): self
    {
        $this->dateEndWork = $dateEndWork;

        return $this;
    }
}
