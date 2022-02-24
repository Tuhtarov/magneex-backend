<?php

namespace App\Controller;

use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/api/users', name: 'api_user_')]
class UserController extends AbstractApiController
{

    public function __construct(private UserRepository $repository)
    {
    }

    #[Route('/', name: 'all', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $users = $this->repository->findAll();

        if (empty($users)) {
            throw new BadRequestException('Users is empty');
        }

        return $this->respond(['users' => $users]);
    }

    #[Route('/current', name: 'current', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function current(): Response
    {
        $user = $this->getUser();

        if ($user) {
            return $this->respond(['user' => $user]);
        }

        throw new BadRequestException('Current user is not found');
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $data = $request->toArray();

        $user = $this->repository->create(
            $data['login'],
            $data['password'],
            (int)$data['employeeId']
        );

        return $this->respond(['user' => $user]);
    }
}
