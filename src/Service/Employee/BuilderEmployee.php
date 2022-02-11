<?php

namespace App\Service\Employee;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\People;
use App\Entity\Role;

class BuilderEmployee
{
    private Employee $employee;

    public function __construct()
    {
        $this->employee = new Employee();
    }

    public function create(People $people): self
    {
        $this->employee->setPeople($people);
        return $this;
    }

    public function setRole(?Role $role): self
    {
        $this->employee->setRole($role);
        return $this;
    }

    public function setJobPosition(?JobPosition $jobPosition): self
    {
        $this->employee->setJobPosition($jobPosition);
        return $this;
    }

    public function build(): Employee
    {
        return $this->employee;
    }
}