<?php

namespace App\Service\Employee;

use App\Entity\Employee;
use Exception;
use Symfony\Component\Security\Core\Security;

class CurrentEmployee
{
    public function __construct(private Security $security)
    {
    }

    /**
     * @throws Exception
     */
    public function getEmployee(): Employee
    {
        try {
            return $this->security->getUser()->getEmployee();
        } catch (Exception $e) {
            throw new \RuntimeException("Don't find employee for current user.");
        }
    }
}