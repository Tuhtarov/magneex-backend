<?php

namespace App\Service\Visit;

use App\Entity\Employee;

interface IVisitChecker
{
    public function todayWorkIsCompleted(Employee $employee): bool;
}