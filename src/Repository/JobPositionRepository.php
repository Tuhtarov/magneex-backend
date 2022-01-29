<?php

namespace App\Repository;

use App\Entity\JobPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobPosition[]    findAll()
 * @method JobPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobPosition::class);
    }

    public function firstOrCreate(string $name): JobPosition
    {
        $position = $this->findBy(['name' => $name]);

        if (!$position) {
            $position = $this->create($name);
        }

        return $position;
    }

    private function create(string $name): JobPosition
    {
        $position = new JobPosition();
        $position->setName($name);

        $this->getEntityManager()->persist($position);
        $this->getEntityManager()->flush($position);

        return $position;
    }
    // /**
    //  * @return JobPosition[] Returns an array of JobPosition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobPosition
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
