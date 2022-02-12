<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/roles', name: 'api_roles_')]
class RoleController extends AbstractApiController
{
    public function __construct(private RoleRepository $roleRepository)
    {
    }

    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $roles = $this->roleRepository->findAll();

        if (empty($roles)) {
            throw new BadRequestException('Roles is not found');
        }

        return $this->respond(['roles' => $roles]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $name = $request->request->get('name');

        $role = $this->roleRepository->findBy(['name' => $name]);

        if (!$role) {
            $role = $this->roleRepository->create($name);
        }

        return $this->respond(['role' => $role], Response::HTTP_CREATED);
    }
}
