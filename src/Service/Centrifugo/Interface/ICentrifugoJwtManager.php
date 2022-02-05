<?php

namespace App\Service\Centrifugo\Interface;

use App\Entity\User;

interface ICentrifugoJwtManager
{
    /**
     * Сгенерировать пользовательский токен для взаимодействия с Centrifuge
     * @param User $user
     * @return string
     */
    public function generateToken(User $user): string;
}