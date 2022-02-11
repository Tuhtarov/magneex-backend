<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Service\Employee\RequestBuilderEmployee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    private RequestBuilderEmployee $builderEmployee;

    public function __construct(ManagerRegistry $registry, RequestBuilderEmployee $builder)
    {
        parent::__construct($registry, Employee::class);
        $this->builderEmployee = $builder;
    }

    public function createFromRequest(Request $request): ?Employee
    {
        $employee = $this->builderEmployee->createFromRequest($request);

        if ($employee) {
            $this->getEntityManager()->persist($employee);
            $this->getEntityManager()->flush();
            return $employee;
        }

        return null;
    }

    /**
     * TRUE - сущность удалена | FALSE - ошибка удаления.
     * @param int $id
     * @return bool
     */
    public function findAndDeleteById(int $id): bool
    {
        $employee = $this->find($id);

        if ($employee) {
            $this->deleteEntity($employee);
            return true;
        }

        return false;
    }

    public function deleteEntity(Employee $employee): void
    {
        $this->getEntityManager()->remove($employee);
        $this->getEntityManager()->flush();
    }
}
