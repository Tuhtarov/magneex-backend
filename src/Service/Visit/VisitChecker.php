<?php

namespace App\Service\Visit;

use App\Entity\Employee;
use App\Repository\VisitRepository;

class VisitChecker implements IVisitChecker
{
    private VisitRepository $repository;

    public function __construct(VisitRepository $repository)
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