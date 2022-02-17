<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/api/roles', name: 'api_roles_')]
#[IsGranted('ROLE_ADMIN')]
class RoleController extends AbstractApiController
{
    public function __construct(private RoleRepository $repository)
    {
    }

    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $roles = $this->repository->findAll();

        if (empty($roles)) {
            throw new BadRequestException('Roles not found');
        }

        return $this->respond(['roles' => $roles]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $name = $request->request->get('name');

        if (empty($name)) {
            throw new BadRequestException('Role name is empty');
        }

        return $this->respond(['role' => $this->repository->firstOrCreate($name)], Response::HTTP_CREATED);
    }
}
