<?php

namespace App\Controller;

use App\Entity\User;
use http\Message\Body;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    private $tokenGenerator;

    public function __construct(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): Response
    {
//        if ($user === null) {
//            return $this->json([
//                'message' => 'Missing credentials in body request',
//            ], Response::HTTP_UNAUTHORIZED);
//        }
//

        dump($this->getUser());
        die();
        return $this->json([
            'user' => 'hello'
        ]);
//
//        return $this->json([
//            'user' => $user->getUserIdentifier(),
//            'token' => $this->tokenGenerator->generateToken(),
//        ], Response::HTTP_ACCEPTED);
    }
}
