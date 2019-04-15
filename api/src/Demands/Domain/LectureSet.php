<?php


namespace Demands\Domain;


use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Users\Domain\User;

class LectureSet
{
    const LECTURE_LECTURE_TYPE = 0;
    const PROJECT_LECTURE_TYPE = 1;
    const LABORATORY_LECTURE_TYPE = 2;
    const EXERCISES_LECTURE_TYPE = 3;

    const LECTURE_TYPES_STRING_TO_INT = [
        'Ćwiczenia' => self::EXERCISES_LECTURE_TYPE,
        'Wykład' => self::LECTURE_LECTURE_TYPE,
        'Projekt' => self::PROJECT_LECTURE_TYPE,
        'Laboratoria' => self::LABORATORY_LECTURE_TYPE
    ];

    const LECTURE_TYPES_INT_TO_STRING = [
        self::EXERCISES_LECTURE_TYPE => 'Ćwiczenia',
        self::LECTURE_LECTURE_TYPE => 'Wykład',
        self::PROJECT_LECTURE_TYPE => 'Projekt',
        self::LABORATORY_LECTURE_TYPE => 'Laboratoria'
    ];

    /**
     * @var int
     */
    private $lectureType;

    /**
     * @var User
     */
    private $lecturer;

    /**
     * @var ArrayCollection<Week>
     */
    private $allocatedWeeks;

    /**
     * @var int
     */
    private $hoursToDistribute;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var Demand
     */
    private $demand;

    public function __construct(int $lectureType)
    {
        $this->lectureType = $lectureType;
        $this->allocatedWeeks = new ArrayCollection();
    }

    public function getLectureType(): int
    {
        return $this->lectureType;
    }

    public function setLecturer(?User $lecturer): self
    {
        $this->lecturer = $lecturer;
        return $this;
    }

    public function getLecturer(): ?User
    {
        return $this->lecturer;
    }

    /**
     * @return Week[]
     */
    public function getAllocatedWeeks(): array
    {
        return $this->allocatedWeeks->toArray();
    }

    public function getDistributedHours(): int
    {
        $distributedHours = 0;
        foreach ($this->getAllocatedWeeks() as $week) {
            $distributedHours += $week->getAllocatedHours();
        }

        return $distributedHours;
    }

    public function getHoursToDistribute(): int
    {
        return $this->hoursToDistribute;
    }

    public function getUndistributedHours(): int
    {
        return $this->hoursToDistribute - $this->getDistributedHours();
    }

    public function setHoursToDistribute($hours): self
    {
        $this->hoursToDistribute = $hours;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function allocateWeek(Week $week): self
    {
        if ($this->allocatedWeeks->contains($week)) {
            throw new Exception("Ten tydzień został już wybrany");
        }

        if ($week->getAllocatedHours() > $this->getUndistributedHours()) {
            throw new Exception("Wybrano za dużo godzin, pozostało " . $this->getUndistributedHours() . " godzin do rozdysponowania");
        }

        $this->allocatedWeeks->add($week);

        return $this;
    }

    public function getAllocatedWeek(int $weekNumber): ?Week
    {
        foreach ($this->getAllocatedWeeks() as $week) {
            if($week->getNumber() === $weekNumber){
                return $week;
            }
        }

        return null;
    }

    public function addNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function getDemand(): Demand
    {
        return $this->demand;
    }

    public function setDemand(Demand $demand): LectureSet
    {
        $this->demand = $demand;
        return $this;
    }

    /**
     * @param int $weekNumber
     * @return int
     */
    public function getAllocatedHoursInWeek(int $weekNumber): int
    {
        $week = $this->getAllocatedWeek($weekNumber);
        if($week) {
            return $week->getAllocatedHours();
        } else {
            return 0;
        }
    }
}
