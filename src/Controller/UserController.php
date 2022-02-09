<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\User\CurrentUser;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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

        if (count($users) > 0) {
            return $this->respond(['users' => $users]);
        }

        throw new BadRequestException('Users is empty');
    }

    #[Route('/current', name: 'current', methods: ['GET'])]
    public function current(CurrentUser $user): Response
    {
        try {
            $userData = $user->getAssoc();
        } catch (\Exception $e) {
            throw new BadRequestException('User is not found');
        }

        return $this->respond(['user' => $userData]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $userData = $request->request->all("user");

        if (!$userData) {
            throw new BadRequestException('In body don`t exist "user" key');
        }

        try {
            $user = $this->userRepository->create($userData);
        } catch (UniqueConstraintViolationException $exception) {
            return $this->respond(['message' => 'Duplicate key'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($user) {
            return $this->respond(['user' => $user]);
        }

        throw new BadRequestException('User is not created');
    }
}
