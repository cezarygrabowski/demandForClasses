<?php


namespace App\Domain\demands;


use App\Domain\users\User;
use Doctrine\Common\Collections\ArrayCollection;
use Rhumsaa\Uuid\Uuid;

class Demand
{
    public const STATUS_UNTOUCHED = 0;
    public const STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER = 1;
    public const STATUS_ACCEPTED_BY_TEACHER = 2;
    public const STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER = 3;
    public const STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR = 4;
    public const STATUS_ACCEPTED_BY_DEAN = 5;

    public const STATUSES = [
        self::STATUS_UNTOUCHED => 'Wygenerowane',
        self::STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER => 'Przypisane przez kierownika zakładu',
        self::STATUS_ACCEPTED_BY_TEACHER => 'Zaakceptowane i wypełnione przez nauczyciela',
        self::STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER => 'Zaakceptowane przez kierownika zakładu',
        self::STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR => 'Zaakceptowane przez dyrektora instytutu',
        self::STATUS_ACCEPTED_BY_DEAN => 'Zaakceptowane przez dziekana'
    ];

    /** @var Uuid */
    private $uuid;

    /** @var Subject */
    private $subject;

    /** @var ArrayCollection<LectureSet> */
    private $lectureSets;

    /** @var int */
    private $status;

    /** @var string */
    private $schoolYear;

    /** @var Group */
    private $group;

    /** @var string */
    private $semester;

    /** @var string */
    private $institute;

    /** @var string */
    private $department;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
        $this->uuid = Uuid::uuid4();
        $this->lectureSets = new ArrayCollection();
        $this->status = self::STATUS_UNTOUCHED;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getUuid(): string
    {
        return $this->uuid->toString();
    }

    public function addLectureSet(LectureSet $lectureSet): self
    {
        if (!$this->lectureSets->contains($lectureSet)) {
            $this->lectureSets->add($lectureSet);
        }

        return $this;
    }

    public function changeLecturer(User $lecturer, int $lectureType)
    {
        foreach ($this->getLectureSets() as $lectureSet) {
            if ($lectureSet->getLectureType() === $lectureType) {
                $lectureSet->setLecturer($lecturer);
            }
        }
    }

    /**
     * @return LectureSet[]
     */
    public function getLectureSets(): array
    {
        return $this->lectureSets->toArray();
    }

    public function getLectureSet(int $lectureType): ?LectureSet
    {
        foreach ($this->getLectureSets() as $lectureSet) {
            if ($lectureSet->getLectureType() === $lectureType) {
                return $lectureSet;
            }
        }

        return null;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getSchoolYear(): string
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(string $schoolYear): Demand
    {
        $this->schoolYear = $schoolYear;
        return $this;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): Demand
    {
        $this->group = $group;
        return $this;
    }

    public function getSemester(): string
    {
        return $this->semester;
    }

    public function setSemester(string $semester): Demand
    {
        $this->semester = $semester;
        return $this;
    }

    public function getInstitute(): string
    {
        return $this->institute;
    }

    public function setInstitute(string $institute): Demand
    {
        $this->institute = $institute;
        return $this;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function setDepartment(string $department): Demand
    {
        $this->department = $department;
        return $this;
    }
}
