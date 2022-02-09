<?php

namespace App\Controller;

use App\Entity\JobPosition;
use App\Repository\JobPositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/job-positions', name: 'api_job_position')]
class JobPositionController extends AbstractApiController
{
    private JobPositionRepository $jobPositionRepository;

    public function __construct(JobPositionRepository $jobPositionRepository)
    {
        $this->jobPositionRepository = $jobPositionRepository;
    }

    #[Route('/', name: 'get_all')]
    public function index(): Response
    {
        $positions = $this->jobPositionRepository->findAll();

        if (empty($positions)) {
            return $this->respond(['message' => 'fuck']);
        }

        return $this->respond(['message' => 'all god', 'jobPositions' => $positions]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $name = $request->request->get('name');

        if (empty($name)) {
            throw new BadRequestException('Name is empty');
        }

        $position = $this->jobPositionRepository->firstOrCreate($name);

        return $this->respond(['jobPosition' => $position], Response::HTTP_CREATED);
    }
}
