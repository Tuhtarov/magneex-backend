<?php

namespace App\Controller;

use App\Entity\QR;
use App\Entity\User;
use App\Repository\QRRepository;
use App\Service\Centrifugo\Interface\IRealTimeServer;
use App\Service\Visit\IVisitRecorder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/qr', name: 'api_qr_')]
class QRController extends AbstractApiController
{
    public function __construct(
        private IRealTimeServer $realTimeServer,
        private IVisitRecorder  $visitRecorder,
        private QRRepository    $qrRepo
    ) {
    }

    #[Route('/connection', name: 'subscribe', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function subscribeConnection(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->realTimeServer->subscribe($user);
        $connectionConfig = $this->realTimeServer->getConfigForSubscriber($user);

        // публикуем QR
        $qr = $this->qrRepo->create();
        $this->publishQr($qr);

        return $this->respond(['config' => $connectionConfig, 'qr' => $qr], Response::HTTP_CREATED);
    }

    #[Route('/connection/close', name: 'unsubscribe', methods: ['POST'])]
    public function unsubscribeConnection(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->realTimeServer->unsubscribe($user);

        return $this->respond(['message' => "Unsubscribed user: {$user->getLogin()}"]);
    }

    #[Route('/scan/{token}/{id<\d+>?}', name: 'scan', methods: ['GET'])]
    public function scanQr(string $token, ?int $id = null): Response
    {
        $qr = $this->qrRepo->fetchQrBy($token, $id);
        $employee = $this->getCurrentEmployee();

        // учитываем посещение
        $visit = $this->visitRecorder->record($employee, $qr);
        $this->publishQR($this->qrRepo->create());

        return $this->respond(['visit' => $visit], Response::HTTP_CREATED);
    }

    private function publishQr(QR $qr): void
    {
        $this->realTimeServer->publish([
            'qr' => [
                'id' => $qr->getId(),
                'token' => $qr->getToken()
            ]
        ]);
    }
}
