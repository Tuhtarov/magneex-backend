<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\User;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractApiController extends AbstractController
{
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
            [new DateTimeNormalizer(), new ObjectNormalizer($classMetadataFactory)],
            ['json' => new JsonEncoder()]
        );

        $json = $serializer->normalize($data, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                '__cloner__',
                '__initializer__',
                '__isInitialized__',
                'timezone',
                'offset'
            ],
        ]);

        return $this->json($json, $code);
    }
}