<?php

namespace App\Controller;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/roles', name: 'api_roles_')]
class RoleController extends AbstractApiController
{
    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(EntityManagerInterface $manager): Response
    {
        $roles = $manager->getRepository(Role::class)->findAll();

        if (count($roles) > 0) {
            return $this->respond(['roles' => $roles]);
        }

        return $this->json(['message' => 'all bad'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $name = $request->request->get('name');

        /** @var $roleRepo RoleRepository */
        $roleRepo = $manager->getRepository(Role::class);
        $role = $roleRepo->findBy(['name' => $name]);

        if (!$role) {
            $role = $roleRepo->create($name);
        }

        return $this->respond([
            'role' => $role
        ]);
    }
}
