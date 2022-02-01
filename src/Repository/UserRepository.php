<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private UserPasswordHasherInterface $pwdHash;

    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $pwdHash)
    {
        parent::__construct($registry, User::class);
        $this->pwdHash = $pwdHash;
    }

    public function create(array $data): ?User
    {
        $dataExists = !empty($data['login']) && !empty($data['password']) && !empty($data['employeeId']);

        if (!$dataExists) return null;

        $manager = $this->getEntityManager();
        $employee = $manager->getRepository(Employee::class)->find($data['employeeId']);

        if ($employee) {
            $user = new User();
            $user->setLogin($data['login'])->setEmployee($employee);

            $activateValue = isset($data['activated']) && ($data['activated'] === true || $data['activated'] === 'true');
            $user->setActivated($activateValue);

            $this->setPassword($user, $data['password']);

            $manager->persist($user);
            $manager->flush($user);

            return $user;
        }

        return null;
    }

    private function setPassword(User &$user, string $password)
    {
        $hashedPassword = $this->pwdHash->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
