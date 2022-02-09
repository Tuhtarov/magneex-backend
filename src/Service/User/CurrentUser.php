<?php

namespace App\Service\User;

use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Security\Core\Security;

class CurrentUser
{
    private User $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    /**
     * Массив с данными о текущем пользователе.
     * @return array
     */
    #[ArrayShape(['login' => "mixed", 'people' => "mixed", 'role' => "mixed"])]
    public function getAssoc(): array
    {
        $employee = $this->user->getEmployee();

        return [
            'login' => $this->user->getLogin(),
            'people' => $employee->getPeople()->getAssocData(),
            'role' => $employee->getRole()->getName()
        ];
    }

}