<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\QR;
use App\Entity\User;
use App\Repository\QRRepository;
use App\Service\Employee\VisitChecker;
use App\Service\QrCentrifugo;
use App\Service\VisitRecorder\IVisitRecorder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('api/qr', name: 'api_qr_')]
class QRCentrifugoController extends AbstractApiController
{
    private QrCentrifugo $centrifugo;
    private EntityManagerInterface $doctrine;
    private IVisitRecorder $recorder;
    private QRRepository $qrRepo;
    private VisitChecker $visitChecker;

    public function __construct(
        QrCentrifugo $centrifugo,
        EntityManagerInterface $doctrine,
        IVisitRecorder $recorder,
        VisitChecker $visitChecker,
        QRRepository $qrRepo
    ) {
        $this->centrifugo = $centrifugo;
        $this->doctrine = $doctrine;
        $this->recorder = $recorder;
        $this->visitChecker = $visitChecker;
        $this->qrRepo = $qrRepo;
    }

    #[Route('/connection', name: 'subscribe_connection', methods: ['GET'])]
    public function subscribeConnection(): Response
    {
        $user = $this->getUserIfAdmin();
        $this->centrifugo->subscribe($user);
        $qr = $this->qrRepo->create();

        return $this->respond([
            'config' => [
                'url' => $this->centrifugo->getUrlConnection(),
                'token' => $this->centrifugo->generateToken($user),
                'channel' => $this->centrifugo->getChannel()
            ],
            'qr' => [
                'id' => $qr->getId(),
                'token' => $qr->getToken()
            ]
        ]);
    }

    #[Route('/connection/close', name: 'unsubscribe_connection', methods: ['POST'])]
    public function unsubscribeConnection(): Response
    {
        $user = $this->getUserIfAdmin();
        $this->centrifugo->unsubscribe($user);

        return $this->respond(['message' => "Unsubscribed user: {$user->getLogin()}"]);
    }

    #[Route('/scan/{token}/{id<\d+>?}', name: 'scan', methods: ['GET'])]
    public function scanQr(string $token, ?int $id = null): Response
    {
        try {
            $qr = $this->doctrine->getRepository(QR::class)->fetchQrBy($token, $id);
            $employee = $this->getCurrentEmployee();
            $workCompleted = $this->visitChecker->todayWorkIsCompleted($employee);

            if ($workCompleted) {
                return $this->respond(['message' => 'Your work is completed'], Response::HTTP_CONFLICT);
            }

            $visit = $this->recorder->record($employee, $qr);
            $this->centrifugo->publishQR($this->qrRepo->create());

            return $this->respond([
                'visit' => $visit
            ], Response::HTTP_CREATED);

        } catch (\RuntimeException $e) {
            throw new BadRequestException($e);
        }
    }
}
