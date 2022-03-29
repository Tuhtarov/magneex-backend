<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\VisitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/visits', name: 'api_visit_')]
class VisitController extends AbstractApiController
{
    public function __construct(private VisitRepository $repository)
    {
    }

    #[Route('/{id<\d+>?}', name: 'all_history', methods: ['GET'])]
    public function index(?Employee $employee): Response
    {
        $visits = $employee ?
            $this->repository->findHistoryByEmployee($employee) : $this->repository->findAll();

        return $this->respond(['visits' => $visits]);
    }

    #[Route('/today-history/{id<\d+>?}', name: 'today_history', methods: ['GET'])]
    public function todayHistory(?Employee $employee): Response
    {
        $visit = $this->repository->findTodayVisit($employee ?? $this->getCurrentEmployee());

        return $this->respond(['visit' => $visit]);
    }

    #[Route('/my-all-history', name: 'my_all_history', methods: ['GET'])]
    public function myAllHistory(): Response
    {
        $employee = $this->getCurrentEmployee();
        $visits = $this->repository->findHistoryByEmployee($employee);

        return $this->respond(['visits' => $visits]);
    }
}
