<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitRepository::class)]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $begin_work_time;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $end_work_time;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'visits')]
    private $employee;

    public function getCurrentTime(): DateTime
    {
        return new DateTime();
    }

    public function getWorkDate(): ?string
    {
        return $this->begin_work_time?->format('Y.m.d');
    }

    public function getBeginWorkTime(): ?string
    {
        return $this->begin_work_time?->format('H:i:s');
    }

    public function getEndWorkTime(): ?string
    {
        return $this->end_work_time?->format('H:i:s');
    }

    public function setBeginWorkTime(?\DateTimeInterface $begin_work_time): self
    {
        $this->begin_work_time = $begin_work_time;

        return $this;
    }

    public function setEndWorkTime(?\DateTimeInterface $end_work_time): self
    {
        $this->end_work_time = $end_work_time;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
