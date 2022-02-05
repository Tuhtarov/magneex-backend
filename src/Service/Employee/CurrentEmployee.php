<?php

namespace App\Service\Employee;

use App\Entity\Employee;
use App\Entity\User;
use Exception;
use Symfony\Component\Security\Core\Security;

class CurrentEmployee
{
    private User $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    /**
     * @throws Exception
     */
    public function getEmployee(): Employee
    {
        try {
            return $this->user->getEmployee();
        } catch (Exception $e) {
            throw new \RuntimeException("Don't find employee for current user.");
        }
    }
}