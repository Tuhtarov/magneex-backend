<?php

namespace App\Service\Employee;

use App\Entity\Employee;
use App\Entity\Visit;
use App\Repository\VisitRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class VisitChecker
{
    private VisitRepository $repository;

    public function __construct(\App\Repository\VisitRepository $repository)
    {
        $this->repository = $repository;
    }

    public function todayWorkIsCompleted(Employee $employee): bool
    {
        $visit = $this->repository->findTodayVisit($employee);

        if ($visit) {
            return $visit->getBeginWorkTime() && $visit->getEndWorkTime();
        }

        return false;
    }
}