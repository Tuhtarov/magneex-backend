<?php

namespace App\Service\Centrifugo\Interface;

use App\Entity\User;

interface IRealTimeServer
{
    public function getConfigForSubscriber(User $user): array;

    public function subscribe(User $user): void;

    public function unsubscribe(User $user): void;

    public function publish(array $data): void;
}