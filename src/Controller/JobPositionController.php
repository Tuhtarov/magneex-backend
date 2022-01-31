<?php

namespace App\Controller;

use App\Entity\JobPosition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/job-positions', name: 'api_job_position')]
class JobPositionController extends AbstractApiController
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    #[Route('/', name: 'get_all')]
    public function index(): Response
    {
        $positions = $this->manager->getRepository(JobPosition::class)->findAll();

        if (count($positions) > 0) {
            return $this->respond(['message' => 'all god', 'jobPositions' => $positions]);
        }

        return $this->respond(['message' => 'fuck']);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $name = $request->request->get('name') ?? null;

        if (empty($name)) {
            return $this->respond(['Name is empty'], Response::HTTP_BAD_REQUEST);
        }

        $position = $this->manager->getRepository(JobPosition::class)->firstOrCreate($name);

        return $this->respond(['job_position' => $position], Response::HTTP_CREATED);
    }
}
