<?php


namespace Demands\Domain;


use App\Entity\Lecture;
use DateTime;
use Demands\Domain\Update\DetailsToUpdate;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Users\Domain\User;
use Zend\EventManager\Exception\DomainException;

class Demand
{
    public const STATUS_UNTOUCHED = 0;
    public const STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER = 1;
    public const STATUS_ACCEPTED_BY_TEACHER = 2;
    public const STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER = 3;
    public const STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR = 4;
    public const STATUS_ACCEPTED_BY_DEAN = 5;
    public const STATUS_EXPORTED = 6;
    public const DECLINED_BY_TEACHER = 7;

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

    /**
     * @var User
     */
    private $importedBy;

    /**
     * @var DateTime
     */
    private $importedAt;

    public function __construct()
    {
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
        return $this->uuid;
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

    public function getTranslatedStatus(): string
    {
        return self::STATUSES_INT_TO_STRING[$this->status];
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

    public function decline(User $user)
    {
        if (!$user->isTeacher()) {
            throw new \Exception("Tylko nauczyciel może odrzucać zapotrzebowania!");
        }

        foreach ($this->getLectureSets() as $lectureSet) {
            if ($lectureSet->getLecturer() && $lectureSet->getLecturer()->getUuid() === $user->getUuid()) {
                $lectureSet->setLecturer(null);
            }
        }

        $this->setStatus(self::DECLINED_BY_TEACHER);
    }

    public function assign(User $assignor, int $lectureType, User $assignee)
    {
        if (!$assignee->isTeacher()) {
            throw new \Exception("Zapotrzebowanie można przypisywać tylko do nauczyciela!");
        }

        if (!$assignor->isDepartmentManager()) {
            throw new \Exception("Tylko kierownik zakładu może przypisywać zapotrzebowania");
        }

        $lectureSet = $this->getLectureSet($lectureType);
        if(!$lectureSet) {
            throw new DomainException("Na tym zapotrzebowaniu nie znaleziono takiego rodzaju zajęć: " . LectureSet::LECTURE_TYPES_INT_TO_STRING[$lectureType]);
        }

        $lectureSet->setLecturer($assignee);
        $lectureSet->setAssignedAt(new DateTime());
        $lectureSet->setAssignedBy($assignor);
    }

    /**
     * @return User
     */
    public function getExportedBy(): ?User
    {
        return $this->exportedBy;
    }

    /**
     * @return DateTime
     */
    public function getExportedAt(): ?DateTime
    {
        return $this->exportedAt;
    }

    /**
     * @param Subject $subject
     * @return Demand
     */
    public function setSubject(Subject $subject): Demand
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param User $exportedBy
     * @return Demand
     */
    public function setExportedBy(User $exportedBy): Demand
    {
        $this->exportedBy = $exportedBy;
        return $this;
    }

    /**
     * @param DateTime $exportedAt
     * @return Demand
     */
    public function setExportedAt(DateTime $exportedAt): Demand
    {
        $this->exportedAt = $exportedAt;
        return $this;
    }

    public function getImportedBy(): User
    {
        return $this->importedBy;
    }

    public function setImportedBy(User $importedBy): Demand
    {
        $this->importedBy = $importedBy;
        return $this;
    }

    public function getImportedAt(): DateTime
    {
        return $this->importedAt;
    }

    public function setImportedAt(DateTime $importedAt): Demand
    {
        $this->importedAt = $importedAt;
        return $this;
    }
}
