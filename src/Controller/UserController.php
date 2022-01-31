<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserFetcherForResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user', name: 'api_user_')]
class UserController extends AbstractApiController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/all', name: 'all', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->doctrine->getRepository(User::class)->findAll();

        if (count($users) > 0) {
            return $this->respond(['users' => $users]);
        }

        return $this->respond(['message' => 'Users is empty'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/current', name: 'current', methods: ['GET'])]
    public function current(UserFetcherForResponse $userResponse): Response
    {
        $userData = $userResponse->fetchAssoc();

        if ($userData) {
            $code = Response::HTTP_OK;
            $responseBody = $userResponse->fetchAssoc();
        } else {
            $code = Response::HTTP_I_AM_A_TEAPOT;
            $responseBody = ['message' => 'User is not found'];
        }

        return $this->json($responseBody, $code);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $userData = $request->request->all("user");

        if (!$userData) {
            return $this->respond(['message' => 'In body don`t exist "user" key'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->doctrine->getRepository(User::class)->create($userData);

        if ($user) {
            return $this->respond(['user' => $user]);
        }

        return $this->respond(['message' => 'User is not created'], Response::HTTP_BAD_REQUEST);
    }
}
