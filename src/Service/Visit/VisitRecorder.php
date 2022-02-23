<?php

namespace App\Service\Visit;

use App\Entity\Employee;
use App\Entity\QR;
use App\Entity\Visit;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class VisitRecorder implements IVisitRecorder
{
    public function __construct(private EntityManagerInterface $doctrine, private IVisitChecker $visitChecker)
    {
    }

    public function record(Employee $employee, QR $qr): Visit
    {
        $workIsCompleted = $this->visitChecker->todayWorkIsCompleted($employee);

        if ($workIsCompleted) {
            throw new ConflictHttpException('Your work is completed');
        }

        $visit = $this->doctrine->getRepository(Visit::class)->createVisit($employee);

        $this->doctrine->remove($qr);
        $this->doctrine->flush($qr);

        return $visit;
    }
}