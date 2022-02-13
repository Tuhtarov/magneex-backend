<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/employees', name: 'api_employees_')]
class EmployeeController extends AbstractApiController
{
    public function __construct(private EmployeeRepository $employeeRepository)
    {}

    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $employees = $this->employeeRepository->findAll();

        if (empty($employees)) {
            throw $this->createNotFoundException('Employees is empty');
        }

        return $this->respond(['employees' => $employees]);
    }

    #[Route('/get/{id}', name: 'show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->respond(['employee' => $employee]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Employee $employee): Response
    {
        $this->employeeRepository->deleteEntity($employee);

        return $this->respond(['message' => "Employee deleted"]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['UPDATE'])]
    public function edit(Employee $employee): Response
    {
        // TODO допилить редактирование
        return $this->respond(['employee' => $employee]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $employee = $this->employeeRepository->createFromRequest($request);

        if ($employee) {
            return $this->respond(['employee' => $employee]);
        }

        throw new BadRequestException('Employee is not created');
    }
}

