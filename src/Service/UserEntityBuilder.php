<?php

namespace App\Service;

use App\Entity\Employee;
use App\Entity\People;
use App\Entity\Role;
use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Строитель сущности User.
 *
 * Попутно создаёт сущности:
 * № People - данные о человеке;
 * № Role - роль будущего сотрудника
 * (если переданная роль существует, то строитель берёт её);
 * № Employee - сотрудник (People + Role)
 *
 * build() - собрать сущность User, без добавления в базу.
 */
class UserEntityBuilder
{
    private ObjectManager $manager;
    private UserPasswordHasherInterface $hasher;

    private People $people;
    private Role $role;
    private Employee $employee;

    public function __construct(ManagerRegistry $manager, UserPasswordHasherInterface $hasher)
    {
        $this->manager = $manager->getManager();
        $this->hasher = $hasher;
    }

    /**
     * Создание сущности для человека.
     * Принимает ассоциативынй массив со свойствами, характерными для сущности People
     * ['name', 'family', 'patronymic', 'phone', 'email']
     * @param array $data
     * @return $this
     */
    public function createPeople(array $data): self
    {
        $people = new People();
        $people
            ->setName($data['name'])
            ->setFamily($data['family'])
            ->setPatronymic($data['patronymic'])
            ->setPhone($data['phone'])
            ->setEmail($data['email']);

        $birthDay = DateTime::createFromFormat('Y-m-d', $data['birthDay']);
        $people->setBirthday($birthDay);

        $this->manager->persist($people);
        $this->people = $people;

        return $this;
    }

    /**
     * Создаёт роль, или находит такую же по имени.
     * @param string $roleName
     * @return $this
     */
    public function createRole(string $roleName): self
    {
        $role = $this->manager
            ->getRepository(Role::class)
            ->findOneBy(['name' => $roleName]);

        if ($role === null) {
            $role = new Role();
            $role->setName($roleName);
            $this->manager->persist($role);
        }

        $this->role = $role;

        return $this;
    }

    /**
     * Создание сотрудника (people + role)
     * @return $this
     */
    public function createEmployee(): self
    {
        $employee = new Employee();

        $employee
            ->setPeople($this->people)
            ->setRole($this->role);

        $this->manager->persist($employee);
        $this->employee = $employee;

        return $this;
    }

    /**
     * Создаёт User, но не записывает его в базу.
     * (Задача клиентского кода)
     *
     * @param string $login
     * @param string $password
     * @return User
     */
    public function build(string $login, string $password): User
    {
        $user = new User();

        $user
            ->setEmployee($this->employee)
            ->setLogin($login)
            ->setActivated(true)
            ->setPassword($password);

        $password = $this->hasher->hashPassword(
            $user,
            $password
        );

        $user->setPassword($password);

        return $user;
    }
}
