<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class ApiTokenAuthenticator implements AuthenticationEntryPointInterface {

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse('auth header required', 401);
    }
}