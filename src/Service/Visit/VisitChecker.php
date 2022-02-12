<?php

namespace App\Service\Visit;

use App\Entity\Employee;
use App\Repository\VisitRepository;

class VisitChecker implements IVisitChecker
{
    public function __construct(private VisitRepository $visitRepository)
    {
    }

    public function todayWorkIsCompleted(Employee $employee): bool
    {
        $visit = $this->visitRepository->findTodayVisit($employee);

        if ($visit) {
            return $visit->getBeginWorkTime() && $visit->getEndWorkTime();
        }

        return false;
    }
}