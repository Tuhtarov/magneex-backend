<?php

namespace App\Controller;

use App\Repository\JobPositionRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/job-positions', name: 'api_job_position')]
class JobPositionController extends AbstractApiController
{
    public function __construct(private JobPositionRepository $jobPositionRepository)
    {
    }

    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $positions = $this->jobPositionRepository->findAll();

        return $this->respond(['jobPositions' => $positions]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $name = $request->request->get('name');

        if (!empty($name)) {
            $position = $this->jobPositionRepository->firstOrCreate($name);

            return $this->respond(['jobPosition' => $position], Response::HTTP_CREATED);
        }

        throw new BadRequestException('Error create');
    }
}
