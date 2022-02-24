<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private UserPasswordHasherInterface $pwdHash)
    {
        parent::__construct($registry, User::class);
    }

    public function create(string $login, string $password, int $employeeId, bool $isActivated = true): User
    {
        $employee = $this->getEntityManager()->getRepository(Employee::class)->find($employeeId);

        if ($employee && !$employee->getUser()) {
            $user = new User();

            $user
                ->setLogin($login)
                ->setPassword($this->pwdHash->hashPassword($user, $password))
                ->setEmployee($employee)
                ->setActivated($isActivated);

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush($user);

            return $user;
        }

        throw new BadRequestHttpException('Employee if not found or account already exist.');
    }
}
