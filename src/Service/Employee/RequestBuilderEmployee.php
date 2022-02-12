<?php

namespace App\Service\Employee;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\Role;
use App\Form\Type\PeopleType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestBuilderEmployee extends BuilderEmployee
{
    public function __construct(
        private FormFactoryInterface   $formFactory,
        private EntityManagerInterface $manager,
        private BuilderEmployee        $builderEmployee
    )
    {
        parent::__construct();
    }

    public function createFromRequest(Request $request): ?Employee
    {
        $form = $this->buildForm(PeopleType::class)->handleRequest($request);
        $peopleIsCreated = $form->isSubmitted() && $form->isValid();

        if ($peopleIsCreated) {
            try {
                $people = $form->getData();
                $role = $this->getRoleFromRequest($request);
                $position = $this->getJobPositionFromRequest($request);

                return $this->builderEmployee
                    ->create($people)
                    ->setRole($role)
                    ->setJobPosition($position)
                    ->build();

            } catch (Exception) {
                return null;
            }
        }

        return null;
    }

    private function getRoleFromRequest(Request $request): ?Role
    {
        $id = $request->request->get(Employee::ROLE_FK_NAME);

        return is_int($id) ?
            $this->manager->getRepository(Role::class)->find($id) : null;
    }

    private function getJobPositionFromRequest(Request $request): ?JobPosition
    {
        $id = $request->request->get(Employee::JOB_POSITION_FK_NAME);

        return is_int($id) ?
            $this->manager->getRepository(JobPosition::class)->find($id) : null;
    }

    private function buildForm(string $type): FormInterface
    {
        $options = ['csrf_protection' => false];
        return $this->formFactory->createNamed('', $type, null, $options);
    }
}

