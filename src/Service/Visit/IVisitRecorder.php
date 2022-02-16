<?php

namespace App\Service\Visit;

use App\DTO\CurrentVisitDTO;
use App\Entity\Employee;
use App\Entity\QR;

interface IVisitRecorder
{
    /**
     * Регистрирует посещение / убытие 🤨?
     *
     * @param Employee $employee
     * @param QR $qr
     * @return CurrentVisitDTO
     */
    public function record(Employee $employee, QR $qr): CurrentVisitDTO;
}
