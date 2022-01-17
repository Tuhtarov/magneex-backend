<?php

namespace App\Controller;

use App\Service\UserFetcherForResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'user', methods: ['POST'])]
    public function index(UserFetcherForResponse $userResponse): Response
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
}
