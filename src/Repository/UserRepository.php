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

    public function __construct(ManagerRegistry $registry, private UserPasswordHasherInterface $pwdHash)
    {
        parent::__construct($registry, User::class);
    }

    public function create(array $data): ?User
    {
        $dataExists = !empty($data['login']) && !empty($data['password']) && !empty($data['employeeId']);

        if (!$dataExists) {
            return null;
        }

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

    private function setPassword(User $user, string $password): void
    {
        $hashedPassword = $this->pwdHash->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
    }
}
