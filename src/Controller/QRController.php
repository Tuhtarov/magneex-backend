<?php

namespace App\Controller;

use App\Entity\QR;
use App\Entity\User;
use App\Repository\QRRepository;
use App\Service\Centrifugo\Interface\IRealTimeServer;
use App\Service\Visit\IVisitChecker;
use App\Service\Visit\IVisitRecorder;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/qr', name: 'api_qr_')]
class QRController extends AbstractApiController
{
    private IRealTimeServer $realTimeServer;
    private IVisitRecorder $visitRecorder;
    private IVisitChecker $visitChecker;
    private QRRepository $qrRepo;

    public function __construct(
        IRealTimeServer $centrifugo,
        IVisitRecorder  $visitRecorder,
        IVisitChecker   $visitChecker,
        QRRepository    $qrRepo
    )
    {
        $this->realTimeServer = $centrifugo;
        $this->visitRecorder = $visitRecorder;
        $this->visitChecker = $visitChecker;
        $this->qrRepo = $qrRepo;
    }

    #[Route('/connection', name: 'subscribe', methods: ['GET'])]
    public function subscribeConnection(): Response
    {
        // подписываем админа
        $user = $this->getUserIfAdmin();
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
        try {
            $qr = $this->qrRepo->fetchQrBy($token, $id);
            $employee = $this->getCurrentEmployee();
            $workIsCompleted = $this->visitChecker->todayWorkIsCompleted($employee);

            if (!$workIsCompleted) {
                // регистрируем посещение
                $visit = $this->visitRecorder->record($employee, $qr);
                $this->publishQR($this->qrRepo->create());

                return $this->respond(['visit' => $visit], Response::HTTP_CREATED);
            }

            return $this->respond(['message' => 'Your work is completed'], Response::HTTP_CONFLICT);

        } catch (\RuntimeException $e) {
            throw new BadRequestException($e);
        }
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
