<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\User;
use Doctrine\Common\Annotations\AnnotationReader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     * Получает связанную сущность People с текущего User.
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


    protected function respond($data, int $code = Response::HTTP_OK): Response
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $serializer = new Serializer(
            [new ObjectNormalizer($classMetadataFactory)],
            ['json' => new JsonEncoder()]
        );

        $json = $serializer->normalize($data, 'json');

        return $this->json($json, $code);
    }
}