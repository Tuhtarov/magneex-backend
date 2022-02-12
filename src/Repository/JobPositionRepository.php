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
        $position = $this->findOneBy(['name' => $name]);

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
}

