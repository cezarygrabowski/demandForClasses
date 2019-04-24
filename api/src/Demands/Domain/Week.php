<?php


namespace Demands\Domain;


use Exception;
use Ramsey\Uuid\Uuid;

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

    private $uuid;

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
     * @var LectureSet
     */
    private $lectureSet;

    /**
     * Week constructor.
     * @param int $number
     * @param int $allocatedHours
     * @param Place $place
     * @throws Exception
     */
    public function __construct(
        int $number,
        int $allocatedHours,
        Place $place = null
    ) {
        $this->uuid = Uuid::uuid4();
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

    public function setLectureSet(LectureSet $lectureSet): Week
    {
        $this->lectureSet = $lectureSet;
        return $this;
    }

    public function getLectureSet(): LectureSet
    {
        return $this->lectureSet;
    }

    /**
     * @param int $allocatedHours
     * @return Week
     */
    public function setAllocatedHours(int $allocatedHours): Week
    {
        $this->allocatedHours = $allocatedHours;
        return $this;
    }
}
