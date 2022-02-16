<?php

namespace App\DTO;

use App\Entity\Visit;
use DateTimeInterface;
use JetBrains\PhpStorm\Pure;

class CurrentVisitDTO
{
    private ?int $id;
    private DateTimeInterface $currentTime;
    private ?DateTimeInterface $beginWorkTime;
    private ?DateTimeInterface $endWorkTime;

    #[Pure]
    public function __construct(Visit $visit, DateTimeInterface $currentTime)
    {
        $this->id = $visit->getId();
        $this->currentTime = $currentTime;
        $this->beginWorkTime = $visit->getBeginWorkTime();
        $this->endWorkTime = $visit->getEndWorkTime();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCurrentTime(): DateTimeInterface
    {
        return $this->currentTime;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getBeginWorkTime(): ?DateTimeInterface
    {
        return $this->beginWorkTime;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndWorkTime(): ?DateTimeInterface
    {
        return $this->endWorkTime;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param DateTimeInterface $currentTime
     */
    public function setCurrentTime(DateTimeInterface $currentTime): void
    {
        $this->currentTime = $currentTime;
    }

    /**
     * @param DateTimeInterface|null $beginWorkTime
     */
    public function setBeginWorkTime(?DateTimeInterface $beginWorkTime): void
    {
        $this->beginWorkTime = $beginWorkTime;
    }

    /**
     * @param DateTimeInterface|null $endWorkTime
     */
    public function setEndWorkTime(?DateTimeInterface $endWorkTime): void
    {
        $this->endWorkTime = $endWorkTime;
    }
}