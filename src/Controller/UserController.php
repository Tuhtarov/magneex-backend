<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users', name: 'api_user_')]
class UserController extends AbstractApiController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Route('/', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        if (empty($users)) {
            throw new BadRequestException('Users is empty');
        }

        return $this->respond(['users' => $users]);
    }

    #[Route('/current', name: 'current', methods: ['GET'])]
    public function current(): Response
    {
        $user = $this->getUser();

        if ($user) {
            return $this->respond(['user' => $user]);
        }

        throw new BadRequestException('Current user is not found');
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $userData = $request->request->all("user");

        if (!empty($userData)) {
            $user = $this->userRepository->create($userData);

            if ($user) {
                return $this->respond(['user' => $user]);
            }
        }

        throw new BadRequestException('Creation error');
    }
}
