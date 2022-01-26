<?php

namespace App\Controller;

use App\Entity\JobPosition;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(Request $request): Response
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
        return $this->respond(['all god']);
    }
}
