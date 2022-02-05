<?php

namespace App\Service;

use App\Entity\QR;

class QrCentrifugo extends Centrifugo\CentrifugoBase
{
    public function publishQR(QR $qr): void
    {
        $this->publish([
            'qr' => [
                'id' => $qr->getId(),
                'token' => $qr->getToken()
            ]
        ]);
    }
}