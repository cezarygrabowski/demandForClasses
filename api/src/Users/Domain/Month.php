<?php


namespace Users\Domain;


use Ramsey\Uuid\UuidInterface;

class Month
{
    /**
     * MONTH_NUMBER => [WEEK_NUMBERS]
     * @var array
     */
    const WEEKS_IN_EACH_MONTH = [
        0 => [1, 2, 3, 4],
        1 => [5, 6, 7, 8],
        2 => [9, 10, 11, 12],
        3 => [13, 14, 15, 16],
        4 => [17, 18, 19, 20]
    ];

    /**
     * @var UuidInterface
     */
    private $uuid;

    private $monthNumber;
    private $workingHours;
    private $calendar;

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

    public function getCalendar(): Calendar
    {
        return $this->calendar;
    }
}