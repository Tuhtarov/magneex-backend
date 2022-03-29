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

    public function getOverwork(): ?string
    {
        if ($this->begin_work_time && $this->end_work_time) {
            $emp = $this->getEmployee();

            if ($emp && $emp->getJobPosition()) {
                $beginJob = $emp->getJobPosition()->getBeginWork();
                $endJob = $emp->getJobPosition()->getEndWork();

                $jobMs = $endJob?->getTimestamp() - $beginJob?->getTimestamp();
                $workMs = $this->getEndWorkTimeFull()->getTimestamp() - $this->getBeginWorkTimeFull()->getTimestamp();

                if ($beginJob && $workMs > $jobMs) {
                    $overwork = $workMs - $jobMs;

                    return round($overwork / 60);
                }
            }
        }

        return null;
    }

    public function getTardy(): ?string
    {
        if ($this->begin_work_time && $this->end_work_time) {
            $emp = $this->getEmployee();

            if ($emp && $emp->getJobPosition()) {
                $beginJob = $emp->getJobPosition()->getBeginWork();

                if ($beginJob) {
                    $beginWork = DateTime::createFromFormat('Y-m-d H:i:s', $beginJob->format('Y-m-d') . ' ' . $this->getBeginWorkTime());

                    return round(($beginWork->getTimestamp() - $beginJob->getTimestamp()) / 60);
                }
            }
        }

        return null;
    }

    public function getBeginWorkTimeFull(): DateTime
    {
        return $this->begin_work_time;
    }

    public function getEndWorkTimeFull(): DateTime
    {
        return $this->end_work_time;
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
