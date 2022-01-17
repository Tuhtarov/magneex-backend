<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
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

//    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
//    public function index(): Response
//    {
//        $user = $this->getUser();
//
//        if (!$user) {
//            return $this->json([
//                'message' => 'Not authorized'
//            ], Response::HTTP_UNAUTHORIZED);
//        }
//
//        if ($user instanceof User) {
//            return $this->json([
//                'token' => $this->tokenGenerator->generateToken(),
//            ], Response::HTTP_OK);
//        } else {
//            return $this->json([
//                'message' => 'Server error on Login Api'
//            ], Response::HTTP_I_AM_A_TEAPOT);
//        }
//    }
}
