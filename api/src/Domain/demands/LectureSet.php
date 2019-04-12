<?php


namespace App\Domain\demands;


use App\Domain\users\User;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class LectureSet
{
    const LECTURE_LECTURE_TYPE = 0;
    const PROJECT_LECTURE_TYPE = 1;
    const LABORATORY_LECTURE_TYPE = 2;
    const EXERCISES_LECTURE_TYPE = 3;

    const LECTURE_TYPES_FOR_IMPORT = [
        'Ćwiczenia' => self::EXERCISES_LECTURE_TYPE,
        'Wykład' => self::LECTURE_LECTURE_TYPE,
        'Projekt' => self::PROJECT_LECTURE_TYPE,
        'Laboratoria' => self::LABORATORY_LECTURE_TYPE
    ];

    const LECTURE_TYPES_FOR_DISPLAY = [
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

    public function __construct(int $lectureType)
    {
        $this->lectureType = $lectureType;
        $this->allocatedWeeks = new ArrayCollection();
    }

    public function getLectureType(): int
    {
        return $this->lectureType;
    }

    public function setLecturer(User $lecturer): self
    {
        $this->lecturer = $lecturer;
        return $this;
    }

    public function getLecturer(): User
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

        $this->allocatedWeeks->add($week);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getAllocatedWeek(int $weekNumber): Week
    {
        foreach ($this->getAllocatedWeeks() as $week) {
            if($week->getNumber() === $weekNumber){
                return $week;
            }
        }

        throw new Exception("W danym tygodniu nie ma zalokowanych godzin");
    }

    public function addNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }
}
