<?php

namespace App\Service\Centrifugo;

use App\Entity\User;
use App\Service\Centrifugo\Interface\IRealTimeServer;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use phpcent\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class CentrifugoBase implements IRealTimeServer
{
    private Client $centrifugo;
    private string $urlConnection;
    private string $channel;


    public function __construct(ParameterBagInterface $parameter)
    {
        $this->urlConnection = $parameter->get('centrifugo.host_subscribe');
        $this->channel = $parameter->get('centrifugo.channel');

        $apiKey = $parameter->get('centrifugo.api_key');
        $apiHost = $parameter->get('centrifugo.host_api');
        $hmacKey = $parameter->get('centrifugo.hmac_key');

        $this->centrifugo = new Client($apiHost, $apiKey, $hmacKey);
    }

    /**
     * @return string
     */
    public function getUrlConnection(): string
    {
        return $this->urlConnection;
    }

    /**
     * @param string $channel
     * @return CentrifugoBase
     */
    public function setChannel(string $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return array|bool|float|int|string|null
     */
    public function getChannel(): float|int|bool|array|string|null
    {
        return $this->channel;
    }

    /**
     * Сгенерировать токен для пользователя, по которому он сможет
     * подписаться на обновления.
     * @param User $user
     * @return string
     */
    public function generateToken(User $user): string
    {
        return $this->centrifugo->generateConnectionToken(
            $this->getUniqueStringFromUser($user)
        );
    }

    /**
     * Возвращает ассоциативный массив с конфигом для подключения подписчиков к серверу.
     * @param User $user - подписчик.
     * @return array - конфиг.
     */
    #[ArrayShape(['url' => "string", 'token' => "string", 'channel' => "mixed"])]
    public function getConfigForSubscriber(User $user): array
    {
        return [
            'url' => $this->getUrlConnection(),
            'token' => $this->generateToken($user),
            'channel' => $this->getChannel()
        ];
    }

    /**
     * Подписать пользователя на получение обновлений.
     * @param User $user
     */
    public function subscribe(User $user): void
    {
        $this->centrifugo->subscribe($this->channel,
            $this->getUniqueStringFromUser($user)
        );
    }

    /**
     * Отписать пользователя от обновлений.
     * @param User $user
     */
    public function unsubscribe(User $user): void
    {
        $this->centrifugo->unsubscribe($this->channel,
            $this->getUniqueStringFromUser($user)
        );
    }

    /**
     * Публикация данных для подписчиков.
     * @param array $data
     * @return void
     */
    public function publish(array $data): void
    {
        if (!$this->channel) {
            throw new \RuntimeException("Don't specified channel");
        }

        $this->centrifugo->publish($this->channel, $data);
    }

    /**
     * Получить уникальную строку из сущности,
     * для создания уникального пользователя в центрифуге.
     * @param User $user
     * @return string
     */
    #[Pure] private function getUniqueStringFromUser(User $user): string
    {
        return $user->getId() . $user->getLogin();
    }
}