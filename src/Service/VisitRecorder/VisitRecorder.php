<?php

namespace App\Service\VisitRecorder;

use App\Entity\Employee;
use App\Entity\QR;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;

class VisitRecorder implements IVisitRecorder
{
    private EntityManagerInterface $doctrine;

    public function __construct(EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function record(Employee $employee, QR $qr): Visit
    {
        $visit = $this->doctrine->getRepository(Visit::class)->createVisit($employee);
        $this->doctrine->remove($qr);
        $this->doctrine->flush();

        return $visit;
    }
}