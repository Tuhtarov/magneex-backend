<?php

namespace App\Service\Employee;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\People;
use App\Entity\Role;
use App\Form\Type\PeopleType;
use App\Repository\JobPositionRepository;
use App\Repository\RoleRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestBuilderEmployee extends BuilderEmployee
{
    public function __construct(
        private FormFactoryInterface  $formFactory,
        private RoleRepository        $roleRepo,
        private JobPositionRepository $jobPosRepo,
        private BuilderEmployee       $builderEmployee
    )
    {
        parent::__construct();
    }

    public function createFromArray(array $employee): Employee
    {
        $form = $this->formFactory->create(PeopleType::class)->submit($employee);

        if ($form->isSubmitted() && $form->isValid()) {
            $people = $form->getData();
            $role = $this->roleRepo->find($employee['role_id']);
            $position = $this->jobPosRepo->find($employee['job_position_id']);

            return $this->buildEmployee($people, $role, $position);
        }

        throw new BadRequestHttpException('Employee is not created');
    }

    private function buildEmployee(People $people, ?Role $role, ?JobPosition $jb): Employee
    {
        return $this->builderEmployee
            ->create($people)
            ->setRole($role)
            ->setJobPosition($jb)
            ->build();
    }
}

