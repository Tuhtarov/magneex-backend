<?php

namespace App\Repository;

use App\Entity\People;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method People|null find($id, $lockMode = null, $lockVersion = null)
 * @method People|null findOneBy(array $criteria, array $orderBy = null)
 * @method People[]    findAll()
 * @method People[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeopleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, People::class);
    }

    public function edit(People $people, array $data): People
    {
        $people
            ->setFamily($data['family'])
            ->setName($data['name'])
            ->setPatronymic($data['patronymic'])
            ->setBirthday(new DateTime($data['birthday']))
            ->setEmail($data['email'])
            ->setPhone($data['phone']);

        $this->getEntityManager()->persist($people);
        $this->getEntityManager()->flush($people);

        return $people;
    }
}
