<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserEntityBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api', name: 'api_')]
class ApiRegistrationController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private UserEntityBuilder $userBuilder;

    public function __construct(ManagerRegistry $registry, UserEntityBuilder $userBuilder)
    {
        $this->managerRegistry = $registry;
        $this->userBuilder = $userBuilder;
    }

    #[Route('/registration', name: 'registration', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $userData = $this->verifyJsonAndGetUserData($request);

        if (empty($userData)) {
            return $this->getBadJsonResponse('Json body not contains user data');
        }

        $user = $this->createUser($userData['login'], $userData['password']);

        if ($user === null) {
            return $this->getBadJsonResponse('User data contains invalid attributes | values');
        }

        $manager = $this->managerRegistry->getManager();

        $manager->persist($user);
        $manager->flush();

        return $this->json([
            'message' => 'Success registration',
        ], Response::HTTP_CREATED);
    }

    /**
     * Получить данные json из запроса.
     * @param Request $request
     * @return array|null
     */
    private function verifyJsonAndGetUserData(Request $request): ?array
    {
        try {
            $userData = $request->toArray()['user'] ?? null;
        } catch (JsonException $e) {
            $userData = null;
        }

        return $userData;
    }

    /**
     * Создаёт пользователя.
     * Если создание не успешно - возвращает null.
     *
     * @param string $login
     * @param string $password
     * @return User|null
     */
    private function createUser(string $login, string $password): ?User
    {
        return $this->userBuilder
            ->createRole('user')
            ->createPeople(self::mockPeopleData)
            ->createEmployee()
            ->build($login, $password);
    }

    /**
     * Ответить тем, что тело запроса Json не корректно.
     * @param string $message
     * @return JsonResponse
     */
    private function getBadJsonResponse(string $message): JsonResponse
    {
        return $this->json(['message' => $message], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private const mockPeopleData = [
        'name' => 'Slava',
        'family' => 'Kozlovskih',
        'patronymic' => 'Pavlovich',
        'birthDay' => '2001-11-24',
        'phone' => '89232221133',
        'email' => 'duhastvyacheslav@mail.ru'
    ];
}
