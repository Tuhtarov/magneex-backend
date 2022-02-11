<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/employees', name: 'api_employees_')]
class EmployeeController extends AbstractApiController
{
    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function index(): Response
    {
        $employees = $this->employeeRepository->findAll();

        if (empty($employees)) {
            throw $this->createNotFoundException('Employees is empty');
        }

        // CircularReferenceException
        return $this->respond(['employees' => $employees]);
    }

    #[Route('/get/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $employee = $this->employeeRepository->find($id);

        if ($employee) {
            return $this->respond(['employee' => $employee]);
        }

        throw $this->createNotFoundException('Employee is not found');
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $result = $this->employeeRepository->findAndDeleteById($id);

        if ($result) {
            return $this->respond(['message' => "Employee deleted"]);
        }

        throw $this->createNotFoundException("Employee is not found");
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['UPDATE'])]
    public function edit(int $id): Response
    {
        $employee = $this->employeeRepository->find($id);

        if ($employee) {
            // TODO допилить редактирование
            return $this->respond(['id' => $id]);
        }

        throw $this->createNotFoundException("Employee is not found");
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
