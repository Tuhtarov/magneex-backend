<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\ReportVisitsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/report', name: 'api_report_')]
class ReportController extends AbstractApiController
{
    public function __construct(private ReportVisitsRepository $reportRepos)
    {
    }

    // опоздания
    #[Route('/tardies/{id<\d+>?}', name: 'tardies', methods: ['GET'])]
    public function tardies(?Employee $employee): Response
    {
        $tardies = $employee
            ? $this->reportRepos->findTardiesByEmployee($employee)
            : $this->reportRepos->findTardies();

        return $this->respond(['tardies' => $tardies]);
    }

    // переработки
    #[Route('/overworks/{id<\d+>?}', name: 'overworks', methods: ['GET'])]
    public function overworks(?Employee $employee): Response
    {
        $overworks = $employee
            ? $this->reportRepos->findOverworksByEmployee($employee)
            : $this->reportRepos->findOverworks();

        return $this->respond(['overworks' => $overworks]);
    }
}
