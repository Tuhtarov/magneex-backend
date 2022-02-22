<?php

namespace App\Controller;

use App\Repository\JobPositionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/job-positions', name: 'api_job_position_')]
#[IsGranted('ROLE_ADMIN')]
class JobPositionController extends AbstractApiController
{
    public function __construct(private JobPositionRepository $repository)
    {
    }

    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $positions = $this->repository->findAll();

        if (empty($positions)) {
            throw new BadRequestException('Job positions is empty');
        }

        return $this->respond(['jobPositions' => $positions]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $jobPosition = $this->repository->createFromArray($request->toArray());

        return $this->respond(['jobPosition' => $jobPosition], Response::HTTP_CREATED);
    }
}
