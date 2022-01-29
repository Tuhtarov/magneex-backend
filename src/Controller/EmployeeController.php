<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\JobPosition;
use App\Entity\People;
use App\Entity\Role;
use App\Form\Type\PeopleType;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/employees', name: 'api_')]
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

    #[Route('/', name: 'employees_list', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $employees = $doctrine->getManager()->getRepository(Employee::class)->findAll();

        if (!empty($employees)) {
            // return this->json($employees)
            // CircularReferenceException
            return $this->respond(['employees' => $employees]);
        }

        throw $this->createNotFoundException('Employees is empty');
    }

    #[Route('/get/{id}', name: 'employees_show', methods: ['GET'])]
    public function show(int $id, ManagerRegistry $manager): Response
    {
        $employee = $manager->getRepository(Employee::class)->find($id);

        if ($employee) {
            return $this->respond(['employee' => $employee]);
        }

        throw new NotFoundHttpException('Employee is not found');
    }

    #[Route('/delete/{id}', name: 'employees_delete', methods: ['POST'])]
    public function delete(int $id, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $employee = $manager->getRepository(Employee::class)->find($id);

        if ($employee) {
            $manager->remove($employee);
            $manager->flush($employee);

            return $this->respond(['id' => $employee->getId(), 'message' => 'deleted']);
        }

        throw $this->createNotFoundException('No employee found for id ' . $id);
    }

    #[Route('/edit/{id}', name: 'employees_edit', methods: ['POST'])]
    public function edit(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $employee = $entityManager->getRepository(Employee::class)->find($id);

        if (!$employee) {
            throw $this->createNotFoundException('No employee found for id ' . $id);
        }

//        $employee->setName('New employee name!');
//        $entityManager->flush();

        return $this->respond(['id' => $employee->getId()]);
    }

    #[Route('/create', name: 'employees_create', methods: ['POST'])]
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
