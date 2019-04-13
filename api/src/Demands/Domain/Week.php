<?php


namespace Demands\Domain;


class Week
{
    const WEEKS_IN_SEMESTER = [
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15
    ];

    /**
     * @var int
     */
    private $number;

    /**
     * @var int
     */
    private $allocatedHours;

    /**
     * @var Place|null
     */
    private $place;

    /**
     * Week constructor.
     * @param int $number
     * @param int $allocatedHours
     * @param Place $place
     */
    public function __construct(
        int $number,
        int $allocatedHours,
        Place $place = null
    ) {
        $this->number = $number;
        $this->allocatedHours = $allocatedHours;
        $this->place = $place;
    }

    public function getAllocatedHours(): int
    {
        return $this->allocatedHours;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(Place $place): self
    {
        $this->place = $place;
        return $this;
    }
}
