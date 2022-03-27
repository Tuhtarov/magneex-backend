<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/employees', name: 'api_employees_')]
#[IsGranted('ROLE_ADMIN')]
class EmployeeController extends AbstractApiController
{
    public function __construct(private EmployeeRepository $repository)
    {}

    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $employees = $this->repository->findAll();

        if (empty($employees)) {
            throw $this->createNotFoundException('Employees is empty');
        }

        return $this->respond(['employees' => $employees]);
    }

    // сотрудники в онлайне
    #[Route('/online', name: 'online', methods: ['GET'])]
    public function online(): Response
    {
        $employees = $this->repository->findAllOnline();

        return $this->respond(['employees' => $employees]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->respond(['employee' => $employee]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Employee $employee): Response
    {
        $this->repository->deleteEntity($employee);

        return $this->respond(['message' => "Employee deleted"]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['POST'])]
    public function edit(Employee $employee, Request $request): Response
    {
        $newData = $request->toArray()['employee'] ?? null;

        $employee = $this->repository->edit($employee, $newData);

        return $this->respond(['employee' => $employee]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $employee = $this->repository->createFromArray($request->toArray());

        return $this->respond(['employee' => $employee]);
    }
}

