<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\People;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $pwdHash;

    public function __construct(UserPasswordHasherInterface $pwdHash)
    {
        $this->pwdHash = $pwdHash;
    }

    public function load(ObjectManager $manager): void
    {
        $this->createPeoples($manager, 15);
        $manager->flush();
    }

    /**
     * @throws \Exception
     */
    public function createPeoples(ObjectManager $manager, int $qty): void
    {
        $roles = [
            $this->createRole($manager, 'admin'),
            $this->createRole($manager, 'employee')
        ];

        $jobPositions = [
            $this->createJobPosition($manager, 'Дизайнер'),
            $this->createJobPosition($manager, 'Сис админ'),
            $this->createJobPosition($manager, 'Программист'),
            $this->createJobPosition($manager, 'Дотнетчик'),
        ];

        for ($i = 0; $i < $qty; $i++) {
            $people = new People();
            $people
                ->setName("Димка-$i")
                ->setFamily("Новосёлов-$i")
                ->setPatronymic("Моков-$i")
                ->setPhone("12345678901")
                ->setEmail('mock-dimka@gmail.cam')
                ->setBirthday(new \DateTime());

            $employee = $this->createEmployee($manager, $people,
                $roles[random_int(0, count($roles) - 1)],
                $jobPositions[random_int(0, count($roles) - 1)]
            );

            $login = $employee->getRole()->getName();

            $this->createUser($manager, $employee, $login . $i, $login . $i);

            $manager->persist($people);
        }
    }

    public function createRole(ObjectManager $manager, $name): Role
    {
        $role = new Role();
        $role->setName($name);
        $manager->persist($role);

        return $role;
    }

    public function createJobPosition(ObjectManager $manager, $name): JobPosition
    {
        $jobPosition = new JobPosition();
        $jobPosition->setName($name);
        $manager->persist($jobPosition);

        return $jobPosition;
    }

    public function createUser(ObjectManager $manager, Employee $employee, string $login, string $pwd): void
    {
        $user = new User();
        $user->setLogin($login);
        $user->setActivated(true);

        $hashedPassword = $this->pwdHash->hashPassword($user, $pwd);
        $user->setPassword($hashedPassword);
        $user->setEmployee($employee);
        $manager->persist($user);
    }

    public function createEmployee(
        ObjectManager $manager,
        People        $people,
        Role          $role,
        JobPosition   $jobPosition
    ): Employee
    {
        $employee = new Employee();
        $employee->setPeople($people)->setRole($role)->setJobPosition($jobPosition);
        $manager->persist($employee);

        return $employee;
    }
}
