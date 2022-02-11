<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\User\CurrentUser;
use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user', name: 'api_user_')]
class UserController extends AbstractApiController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/all', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        if (!empty($users)) {
            return $this->respond(['users' => $users]);
        }

        throw new BadRequestException('Users is empty');
    }

    #[Route('/current', name: 'current', methods: ['GET'])]
    public function current(CurrentUser $user): Response
    {
        try {
            $userData = $user->getAssoc();
        } catch (Exception) {
            throw new BadRequestException('User is not found');
        }

        return $this->respond(['user' => $userData]);

        /*
        $user = $this->getUser();
        return $this->respond(['user' => $user]);
        */
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $userData = $request->request->all("user");

        if ($userData) {
            $user = $this->userRepository->create($userData);

            if ($user) {
                return $this->respond(['user' => $user]);
            }
        }

        throw new BadRequestException('User is not created');
    }
}
