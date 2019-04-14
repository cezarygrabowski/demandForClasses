<?php


namespace Users\Domain;


class Month
{
    private $monthNumber;
    private $workingHours;

    public function __construct(int $monthNumber, int $workingHours)
    {
        $this->monthNumber = $monthNumber;
        $this->workingHours = $workingHours;
    }

    public function getMonthNumber(): int
    {
        return $this->monthNumber;
    }

    public function getWorkingHours(): int
    {
        return $this->workingHours;
    }
}