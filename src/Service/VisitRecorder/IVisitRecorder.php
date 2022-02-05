<?php

namespace App\Service\VisitRecorder;

use App\Entity\Employee;
use App\Entity\QR;
use App\Entity\Visit;

interface IVisitRecorder
{
    /**
     * Регистрирует посещение / убытие 🤨?
     *
     * @param Employee $employee
     * @param QR $qr
     * @return Visit
     */
    public function record(Employee $employee, QR $qr): Visit;
}
