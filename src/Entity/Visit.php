<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

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
    #[Ignore]
    private $employee;

    public function getBeginWorkTime(): ?\DateTimeInterface
    {
        return $this->begin_work_time;
    }

    public function setBeginWorkTime(?\DateTimeInterface $begin_work_time): self
    {
        $this->begin_work_time = $begin_work_time;

        return $this;
    }

    public function getEndWorkTime(): ?\DateTimeInterface
    {
        return $this->end_work_time;
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
}