<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/visit', name: 'api_visit_')]
class VisitController extends AbstractApiController
{
    public function __construct(private VisitRepository $repository)
    {
    }

    #[Route('/today-history', name: 'today_history', methods: ['GET'])]
    public function index(): Response
    {
        $employee = $this->getCurrentEmployee();
        $visit = $this->repository->findTodayVisit($employee);

        return $this->respond(['visit' => $visit]);
    }
}
