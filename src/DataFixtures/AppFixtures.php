<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\People;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Visit;
use App\Repository\EmployeeRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $pwdHash,
        private EmployeeRepository          $employeeRepository,
        private EntityManagerInterface      $manager)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->createPeoples(15);
        $this->createVisits(75);
    }

    /**
     * @throws \Exception
     */
    public function createPeoples(int $qty): void
    {
        $roles = [
            $this->createRole('admin'),
            $this->createRole('employee')
        ];

        $jobPositions = [
            $this->createJobPosition('Программист'),
            $this->createJobPosition('Дизайнер'),
            $this->createJobPosition('Сис админ'),
            $this->createJobPosition('Дотнетчик'),
        ];

        for ($i = 0; $i < $qty; $i++) {
            $people = new People();
            $people->setBirthday(new \DateTime());

            if ($i === 0) {
                // админ login: 'admin', pwd: 'admin'
                $people
                    ->setName("Александр")
                    ->setFamily("Тухтаров")
                    ->setPatronymic("Анатольевич")
                    ->setPhone("8923228228")
                    ->setEmail('stuxtarov@gmail.cam');

                $employee = $this->createEmployee($people, $roles[0], $jobPositions[0]);
                $login = $employee->getRole()->getName();
            } else if ($i === 1) {
                // сотрудник login: 'employee', pwd: 'employee'
                $people
                    ->setFamily("Козловских")
                    ->setName("Вячеслав")
                    ->setPatronymic("Вячеславович")
                    ->setPhone("13371118901")
                    ->setEmail('mock-employee@gmail.cam');

                $employee = $this->createEmployee($people, $roles[1], $jobPositions[3]);
                $login = $employee->getRole()->getName();
            } else {
                // рандомный дмитрий
                $people
                    ->setName("Димка-$i")
                    ->setFamily("Новосёлов-$i")
                    ->setPatronymic("Моков-$i")
                    ->setPhone("12345678901")
                    ->setEmail("mock-dimka$i@gmail.cam");

                $employee = $this->createEmployee($people,
                    $roles[random_int(0, count($roles) - 1)],
                    $jobPositions[random_int(0, count($roles) - 1)]
                );
                $login = $employee->getRole()->getName() . $i;
            }

            $this->createUser($employee, $login, $login);
        }
    }

    public function createRole($name): Role
    {
        $role = new Role();
        $role->setName($name);
        $this->saveEntity($role);

        return $role;
    }

    public function createJobPosition($name): JobPosition
    {
        $jobPosition = new JobPosition();
        $jobPosition->setName($name);

        $beginTime = DateTime::createFromFormat('Y-m-d H:i', '2021-12-25 08:00');
        $endTime = DateTime::createFromFormat('Y-m-d H:i', '2021-12-25 17:00');

        $jobPosition->setBeginWorkTime($beginTime);
        $jobPosition->setEndWorkTime($endTime);
        $jobPosition->setSalary(random_int(15000, 140000));

        $this->saveEntity($jobPosition);

        return $jobPosition;
    }

    public function createUser(Employee $employee, string $login, string $pwd): void
    {
        $user = new User();
        $user->setLogin($login);
        $user->setActivated(true);

        $hashedPassword = $this->pwdHash->hashPassword($user, $pwd);
        $user->setPassword($hashedPassword);
        $user->setEmployee($employee);

        $this->saveEntity($user);
    }

    public function createEmployee(
        People      $people,
        Role        $role,
        JobPosition $jobPosition
    ): Employee
    {
        $employee = new Employee();
        $employee
            ->setPeople($people)
            ->setRole($role)
            ->setJobPosition($jobPosition);

        $this->saveEntity($employee);

        return $employee;
    }

    private function saveEntity($entity): void
    {
        $this->manager->persist($entity);
        $this->manager->flush($entity);
    }


    /**
     * @throws Exception
     */
    private function createVisits(int $total): void
    {
        $employees = $this->employeeRepository->findAll();
        $employeesCount = count($employees);

        for ($i = 0; $i < $total; $i++) {
            $randEmployee = $employees[random_int(0, $employeesCount - 1)];

            $randDays = random_int(0, $total);

            $randMinutes = random_int(0, $total);
            $randMinutesSecond = random_int(0, $total);

            $randHours = random_int(0, 4);
            $randHoursSecond = $randHours + random_int(0, 4);

            $randBeginTime = new DateTime("-$randDays days -$randHours hours -$randMinutes minutes");
            $randEndTime = new DateTime("-$randDays days +$randHoursSecond hours -$randMinutesSecond minutes");

            $randVisit = new Visit();
            $randVisit
                ->setBeginWorkTime($randBeginTime)
                ->setEndWorkTime($randEndTime)
                ->setEmployee($randEmployee);

            $this->saveEntity($randVisit);
        }
    }
}
