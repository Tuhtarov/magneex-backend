<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Visit;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    public function createVisit(Employee $employee, DateTime $currentTime = new DateTime()): Visit
    {
        $todayVisit = $this->findTodayVisit($employee);

        // если сегодня уже приходил, записываем дату окончания работы
        if ($todayVisit) {
            $todayVisit->setEndWorkTime($currentTime);
            $visit = $todayVisit;
        } else {
            // регистрируем посещение, назначаем время прибытия
            $visit = new Visit();
            $visit->setBeginWorkTime($currentTime);
            $visit->setEmployee($employee);
        }

        $this->getEntityManager()->persist($visit);
        $this->getEntityManager()->flush($visit);

        return $visit;
    }

    public function findTodayVisit(Employee $employee): ?Visit
    {
        $date = new \DateTime();
        $today = $date->format('Y-m-d');

        return $this->createQueryBuilder('v')
            ->where('v.begin_work_time like :today')
            ->andWhere('v.employee = :employee')
            ->setParameters(new ArrayCollection([
                new Parameter('today', $today . '%'),
                new Parameter('employee', $employee)
            ]))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findHistoryByEmployee(Employee $employee): array
    {
        return $this->findBy([
            'employee' => $employee
        ]);
    }
}
