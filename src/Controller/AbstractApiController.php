<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class AbstractApiController extends AbstractFOSRestController
{
    protected function buildForm(string $type, $data = null, array $options = []) : FormInterface
    {
        $options = array_merge($options, [
           'csrf_protection' => false
        ]);

        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }

    /**
     * Получает сотрудника из текущего User.
     * @return Employee
     */
    protected function getCurrentEmployee(): Employee
    {
        /** @var User $user */
        $user = $this->getUser();

        // у текущего пользователя не указан сотрудник
        if (is_null($user->getEmployee())) {
            throw new BadRequestException('For current user not specified employee');
        }

        return $user->getEmployee();
    }

    /**
     * Получает пользователя, если его роль соответствует админской.
     * @return User
     */
    protected function getUserIfAdmin(): User
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user && $user->isAdmin()) {
            return $user;
        }

        throw new AccessDeniedException();
    }

    protected function respond($data, int $code = Response::HTTP_OK): Response
    {
        return $this->handleView($this->view($data, $code));
    }
}