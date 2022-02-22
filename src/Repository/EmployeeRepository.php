<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\People;
use App\Entity\Role;
use App\Form\Type\PeopleType;
use App\Service\Employee\BuilderEmployee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry               $registry,
        private FormFactoryInterface  $formFactory,
        private JobPositionRepository $jobPosRepo,
        private RoleRepository        $roleRepo,
        private BuilderEmployee       $builderEmployee
    )
    {
        parent::__construct($registry, Employee::class);
    }

    public function createFromArray(array $employee): Employee
    {
        $form = $this->formFactory->create(PeopleType::class)->submit($employee);

        $roleId = $employee['role_id'] ?? null;
        $jobPosId = $employee['job_position_id'] ?? null;

        if ($form->isSubmitted() && $form->isValid()) {
            $people = $form->getData();

            // получаем роль и должность по id
            $role = is_int($roleId) ? $this->roleRepo->find($roleId) : null;
            $jobPos = is_int($jobPosId) ? $this->jobPosRepo->find($jobPosId) : null;

            return $this->buildEmployee($people, $role, $jobPos);
        }

        throw new BadRequestHttpException('Employee is not created');
    }

    private function buildEmployee(People $people, ?Role $role, ?JobPosition $jb): Employee
    {
        $employee = $this->builderEmployee
            ->create($people)
            ->setRole($role)
            ->setJobPosition($jb)
            ->build();

        return $this->saveEntity($employee);
    }

    public function deleteEntity(Employee $employee): void
    {
        $this->getEntityManager()->remove($employee);
        $this->getEntityManager()->flush($employee);
    }

    public function saveEntity(Employee $employee): Employee
    {
        $this->getEntityManager()->persist($employee);
        $this->getEntityManager()->flush($employee);

        return $employee;
    }
}

