<?php

namespace App\Service\Visit;

use App\DTO\CurrentVisitDTO;
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

    public function record(Employee $employee, QR $qr): CurrentVisitDTO
    {
        $workIsCompleted = $this->visitChecker->todayWorkIsCompleted($employee);

        if ($workIsCompleted) {
            throw new ConflictHttpException('Your work is completed');
        }

        $currentTime = new DateTime();
        $visit = $this->doctrine->getRepository(Visit::class)->createVisit($employee, $currentTime);
        $currentVisitDto = new CurrentVisitDTO($visit, $currentTime);

        $this->doctrine->remove($qr);
        $this->doctrine->flush();

        return $currentVisitDto;
    }
}