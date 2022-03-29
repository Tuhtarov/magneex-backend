<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\Visit;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportVisitsRepository extends VisitRepository
{
    public function __construct(private LoggerInterface $logger, ManagerRegistry $registry)
    {
        parent::__construct($registry);
    }

    protected function getRSM(): ResultSetMappingBuilder
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Visit::class, 'v');
        return $rsm;
    }

    /** ------------QUERIES------------ */

    private const SQL_FIND_BASE = "
        select * from visit v
        join employee e on v.employee_id = e.id
        join job_position jp on e.job_position_id = jp.id
        join people p on e.people_id = p.id
    ";

    private string $SQL_FIND_TARDIES = self::SQL_FIND_BASE
    . ' where v.begin_work_time is not null 
        and cast(v.begin_work_time as time) > cast(jp.begin_work as time)
    ';

    private string $SQL_FIND_OVERWORKS = self::SQL_FIND_BASE
    . ' where v.begin_work_time is not null and v.end_work_time is not null
        and cast(v.end_work_time as time) > cast(jp.end_work as time)
        and cast(v.begin_work_time as time) <= cast(jp.begin_work as time)
    ';

    private function setFindByEmployee(NativeQuery $q, ?Employee $employee): void
    {
        if ($employee) {
            $q->setSQL($q->getSQL() . ' and e.id = :emp_id');
            $q->setParameter('emp_id', $employee->getId());
        }
    }

    /** ------------METHODS------------ */

    public function findTardiesByEmployee(Employee $employee): array
    {
        return $this->findTardies($employee);
    }

    public function findTardies(?Employee $employee = null): array
    {
        $query = $this->getEntityManager()
            ->createNativeQuery($this->SQL_FIND_TARDIES, $this->getRSM());

        $this->setFindByEmployee($query, $employee);

        return $query->execute();
    }


    public function findOverworksByEmployee(Employee $employee): array
    {
        return $this->findOverworks($employee);
    }

    public function findOverworks(?Employee $employee = null): array
    {
        $query = $this->getEntityManager()
            ->createNativeQuery($this->SQL_FIND_OVERWORKS, $this->getRSM());

        $this->setFindByEmployee($query, $employee);

        return $query->getResult();
    }
}
