<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\People;
use App\Entity\Role;
use App\Form\Type\PeopleType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/api', name: 'api_')]
class EmployeeController extends AbstractApiController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/employees', name: 'employees_list', methods: ['GET'])]
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $employees = $managerRegistry
            ->getRepository(Employee::class)
            ->findAll();

        if (!count($employees) > 0) {
            return $this->json([
                'message' => 'Employees is empty'
            ], Response::HTTP_NO_CONTENT);
        }

        $peoples = [];

        foreach ($employees as $employee) {
            $peoples[] = $employee->getPeople()->getAssocData();
        }

        return $this->json([
            'employees' => $peoples,
        ], Response::HTTP_OK);
    }

    #[Route('/employees/create', name: 'employees_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        /** @var People $people */
        /** @var EmployeeRepository $repository */

        $form = $this->buildForm(PeopleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->entityManager->getRepository(Employee::class);

            try {
                $repository->createEmployee($form->getData());
                $repository->setRole($this->getRoleFromRequest($request));
                $repository->setJobPosition($this->getJobPositionFromRequest($request));

                $repository->persist($this->entityManager)->save();

                return $this->respond($repository->getEntity());
            } catch (\Exception $e) {
                return $this->respond(['message' => 'runtime error'], Response::HTTP_I_AM_A_TEAPOT);
            }

        }

        return $this->respond($form, Response::HTTP_BAD_REQUEST);
    }

    private function getRoleFromRequest(Request $request): ?Role
    {
        $roleId = $request->request->get(Employee::ROLE_FK_NAME);

        if ($roleId !== null) {
            return $this->entityManager->getRepository(Role::class)->find($roleId);
        }

        return null;
    }


    private function getJobPositionFromRequest(Request $request): ?JobPosition
    {
        $jobPositionId = $request->request->get(Employee::JOB_POSITION_FK_NAME);

        if ($jobPositionId !== null) {
            return $this->entityManager->getRepository(JobPosition::class)->find($jobPositionId);
        }

        return null;
    }
}
