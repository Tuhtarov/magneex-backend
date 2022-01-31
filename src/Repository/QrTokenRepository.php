<?php

namespace App\Repository;

use App\Entity\QrToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QrToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method QrToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method QrToken[]    findAll()
 * @method QrToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QrTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QrToken::class);
    }

    // /**
    //  * @return QrToken[] Returns an array of QrToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QrToken
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
