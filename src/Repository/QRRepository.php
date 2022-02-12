<?php

namespace App\Repository;

use App\Entity\QR;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

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

    /**
     * Получить QR по токену, либо по id.
     * (поиск по id быстрее, но если он не был передан, то выполняется поиск по токену)
     * @param string $token
     * @param int|null $id
     * @return QR
     */
    public function fetchQrBy(string $token, ?int $id): QR
    {
        if ($id) {
            $qr = $this->find($id);
        } else {
            $qr = $this->findOneBy(['token' => $token]);
        }

        if (is_null($qr)) {
            throw new RuntimeException("QR not found");
        }

        $this->verifyQR($qr, $token);

        return $qr;
    }

    /**
     * Проверка на пригодность токена.
     */
    private function verifyQR(QR $qr, string $token): void
    {
        // если была попытка обмануть систему
        if (!$qr->isMyToken($token)) {
            throw new RuntimeException("Invalid QR. Token not equal in db record");
        }

        // удаляем просроченный токен
        if ($qr->getIsExpired()) {
            $this->getEntityManager()->remove($qr);
            $this->getEntityManager()->flush();
            throw new RuntimeException("Invalid QR. Token is expired.");
        }
    }

    private function getRandomString(int $length): String
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permitted_chars), 0, $length);
    }
}
