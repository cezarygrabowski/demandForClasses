<?php


namespace Demands\Domain;


use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Users\Domain\User;

class Demand
{
    public const STATUS_UNTOUCHED = 0;
    public const STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER = 1;
    public const STATUS_ACCEPTED_BY_TEACHER = 2;
    public const STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER = 3;
    public const STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR = 4;
    public const STATUS_ACCEPTED_BY_DEAN = 5;
    public const STATUS_EXPORTED = 6;
    const DECLINED_BY_TEACHER = 7;

    public const STATUSES_INT_TO_STRING = [
        self::STATUS_UNTOUCHED => 'Wygenerowane',
        self::STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER => 'Przypisane przez kierownika zakładu',
        self::STATUS_ACCEPTED_BY_TEACHER => 'Zaakceptowane i wypełnione przez nauczyciela',
        self::STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER => 'Zaakceptowane przez kierownika zakładu',
        self::STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR => 'Zaakceptowane przez dyrektora instytutu',
        self::STATUS_ACCEPTED_BY_DEAN => 'Zaakceptowane przez dziekana',
        self::STATUS_EXPORTED => 'Wyeksportowane przez planiste',
        self::DECLINED_BY_TEACHER => 'Odrzucone przez nauczyciela'
    ];

    public const STATUSES_STRING_TO_INT = [
        'Wygenerowane' => self::STATUS_UNTOUCHED,
        'Przypisane przez kierownika zakładu' => self::STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER,
        'Zaakceptowane i wypełnione przez nauczyciela' => self::STATUS_ACCEPTED_BY_TEACHER,
        'Zaakceptowane przez kierownika zakładu' => self::STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER,
        'Zaakceptowane przez dyrektora instytutu' => self::STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR,
        'Zaakceptowane przez dziekana' => self::STATUS_ACCEPTED_BY_DEAN,
        'Wyeksportowane przez planiste' => self::STATUS_EXPORTED,
        'Odrzucone przez nauczyciela' => self::DECLINED_BY_TEACHER
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

    /**
     * @var User
     */
    private $exportedBy;

    /**
     * @var DateTime
     */
    private $exportedAt;

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

    public function markAsExported(User $user): self
    {
        $this->setStatus(self::STATUS_EXPORTED);
        $this->setExportedBy($user);
        $this->setExportedAt(new DateTime());

        return $this;
    }

    private function setExportedBy(User $user)
    {
        $this->exportedBy = $user;
    }

    private function setExportedAt(DateTime $date)
    {
        $this->exportedAt = $date;
    }

    public function isUntouched()
    {
        return $this->getStatus() === self::STATUS_UNTOUCHED;
    }

    public function isAcceptedByDean()
    {
        return $this->getStatus() === self::STATUS_ACCEPTED_BY_DEAN;
    }

    public function isAcceptedByTeacher()
    {
        return $this->getStatus() === self::STATUS_ACCEPTED_BY_TEACHER;
    }
}
