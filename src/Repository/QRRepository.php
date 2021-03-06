<?php

namespace App\Repository;

use App\Entity\QR;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method QR|null find($id, $lockMode = null, $lockVersion = null)
 * @method QR|null findOneBy(array $criteria, array $orderBy = null)
 * @method QR[]    findAll()
 * @method QR[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QRRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QR::class);
    }

    public function create(): QR
    {
        $qr = new QR();
        $qr->setToken($this->getRandomString(9));
        $qr->setIsExpired(false);
        $this->getEntityManager()->persist($qr);
        $this->getEntityManager()->flush($qr);

        return $qr;
    }

    public function fetchByToken(string $token): QR
    {

        $qr = $this->findOneBy(['token' => $token]);

        if (is_null($qr)) {
            throw new NotFoundHttpException("QR not found");
        }

        $this->verifyQR($qr);

        return $qr;
    }

    private function verifyQR(QR $qr): void
    {
        if ($qr->getIsExpired()) {
            $this->getEntityManager()->remove($qr);
            $this->getEntityManager()->flush($qr);

            throw new RuntimeException("Invalid QR. Token is expired.");
        }
    }

    private function getRandomString(int $length): String
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permitted_chars), 0, $length);
    }
}
