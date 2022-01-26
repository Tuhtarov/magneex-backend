<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/roles', name: 'api_roles_')]
class RoleController extends AbstractApiController
{
    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(EntityManagerInterface $manager): Response
    {
        $roles = $manager->getRepository(RoleRepository::class)->findAll();

        if (count($roles) > 0) {
            return $this->respond(['roles' => $roles]);
        }

        return $this->json(['message'], Response::HTTP_BAD_REQUEST);
    }
}
