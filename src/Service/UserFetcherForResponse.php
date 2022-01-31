<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;

class UserFetcherForResponse {
    private $security;

    public function __construct(Security $security)
    {

        $this->security = $security;
    }

    public function fetchAssoc() : ?array
    {
        $user = $this->security->getUser();

        if ($user) {
            $user = [
                'user' => [
                    'login' => $user->getLogin(),
                    'role' => $user->getEmployee()->getRole()->getName(),
                    'people' => $user->getEmployee()->getPeople()->getAssocData()
                ]
            ];
        }

        return $user;
    }
}