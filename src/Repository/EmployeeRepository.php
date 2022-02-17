<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Service\Employee\RequestBuilderEmployee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, private RequestBuilderEmployee $builderEmployee)
    {
        parent::__construct($registry, Employee::class);
    }

    public function createFromArray(array $employeeData): Employee
    {
        $employee = $this->builderEmployee->createFromArray($employeeData);

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

