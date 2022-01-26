<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\People;
use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    private Employee $employee;
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function createEmployee(People $people): self
    {
        $employee = new Employee();

        $employee->setPeople($people);

        $this->employee = $employee;

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

    public function persist(EntityManagerInterface $manager): self
    {
        $this->manager = $manager;
        $this->manager->persist($this->employee);

        return $this;
    }

    public function save()
    {
        $this->manager->flush();
    }



    public function getEntity(): Employee
    {
        return $this->employee;
    }
}
