<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiController extends AbstractFOSRestController
{
    protected function buildForm(string $type, $data = null, array $options = []) : FormInterface
    {
        $options = array_merge($options, [
           'csrf_protection' => false
        ]);

        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }

    protected function respond($data, int $statusCode = Response::HTTP_OK): Response
    {
        return $this->handleView($this->view($data, $statusCode));
    }
}