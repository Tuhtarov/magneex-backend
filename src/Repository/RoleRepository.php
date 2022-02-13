<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    public function firstOrCreate(string $name): Role
    {
        $role = $this->findBy(['name' => $name]);

        if (!$role) {
            $role = $this->create($name);
        }

        return $role;
    }

    public function create(string $name): Role
    {
        $role = new Role();
        $role->setName($name);

        $this->getEntityManager()->persist($role);
        $this->getEntityManager()->flush($role);

        return $role;
    }
}

