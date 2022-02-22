<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/visits', name: 'api_visit_')]
class VisitController extends AbstractApiController
{
    public function __construct(private VisitRepository $repository)
    {
    }

    #[Route('/', name: 'all_history', methods: ['GET'])]
    public function index(): Response
    {
        $visits = $this->repository->findAll();

        return $this->respond(['visits' => $visits]);
    }

    #[Route('/today-history', name: 'today_history', methods: ['GET'])]
    public function todayHistory(): Response
    {
        $employee = $this->getCurrentEmployee();
        $visit = $this->repository->findTodayVisit($employee);

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
