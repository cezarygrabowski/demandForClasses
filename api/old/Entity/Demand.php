<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\PersistentCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="demands")
 * @ORM\Entity
 */
class Demand
{
    const STATUS_UNTOUCHED = 0;
    const STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER = 1;
    const STATUS_ACCEPTED_BY_TEACHER = 2;
    const STATUS_ACCEPTED_BY_KIEROWNIK_ZAKLADU = 3;
    const STATUS_ACCEPTED_BY_DYREKTOR_INSTYTUTU = 4;
    const STATUS_ACCEPTED_BY_DZIEKAN = 5;
    
    const STATUSES = [
        self::STATUS_UNTOUCHED => 'Wygenerowane',
        self::STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER => 'Przypisane przez kierownika zakładu',
        self::STATUS_ACCEPTED_BY_TEACHER => 'Zaakceptowane i wypełnione przez nauczyciela',
        self::STATUS_ACCEPTED_BY_KIEROWNIK_ZAKLADU => 'Zaakceptowane przez kierownika zakładu',
        self::STATUS_ACCEPTED_BY_DYREKTOR_INSTYTUTU => 'Zaakceptowane przez dyrektora instytutu',
        self::STATUS_ACCEPTED_BY_DZIEKAN => 'Zaakceptowane przez dziekana'
    ];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $yearNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $institute;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $department;

    /**
     * @ORM\Column(name="group_name", type="string", nullable=true)
     */
    private $group;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $semester;

    /**
     * @var Subject
     * @ORM\ManyToOne(targetEntity="Subject", cascade={"persist"})
     */
    private $subject;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $groupType;

    /**
     * @OneToMany(targetEntity="Lecture", mappedBy="demand", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     * @var ArrayCollection
     */
    private $lectures;

    public function addLecture(Lecture $lecture) {
        if(!$this->lectures->contains($lecture)) {
            $this->lectures->add($lecture);
        }
    }

    /**
     * @return ArrayCollection<Lecture>
     */
    public function getLectures()
    {
        return $this->lectures;
    }

    /**
     * @param mixed $lectures
     */
    public function setLectures($lectures): void
    {
        $this->lectures = $lectures;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getYearNumber()
    {
        return $this->yearNumber;
    }

    /**
     * @param mixed $yearNumber
     */
    public function setYearNumber($yearNumber): void
    {
        $this->yearNumber = $yearNumber;
    }

    /**
     * @return mixed
     */
    public function getInstitute()
    {
        return $this->institute;
    }

    /**
     * @param mixed $institute
     */
    public function setInstitute($institute): void
    {
        $this->institute = $institute;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group): void
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * @param mixed $semester
     */
    public function setSemester($semester): void
    {
        $this->semester = $semester;
    }

    /**
     * @return mixed
     */
    public function getGroupType()
    {
        return $this->groupType;
    }

    /**
     * @param mixed $groupType
     */
    public function setGroupType($groupType): void
    {
        $this->groupType = $groupType;
    }

    /**
     * @Serializer\VirtualProperty(name="totalHours")
     */
    public function getTotalHours()
    {
        $totalHours = 0;
        /** @var Lecture $lecture */
        foreach ($this->lectures as $lecture) {
            $totalHours += $lecture->getHours();
        }

        return $totalHours;
    }

    /**
     * @param mixed $totalHours
     */
    public function setTotalHours($totalHours): void
    {
        $this->totalHours = $totalHours;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function setSubject(Subject $subject): void
    {
        $this->subject = $subject;
    }

    public function getLecture(int $id): ?Lecture
    {
        /** @var Lecture $lecture */
        foreach ($this->getLectures() as $lecture) {
            if($lecture->getId() === $id) {
                return $lecture;
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function __construct()
    {
        $this->lectures = new ArrayCollection();
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            $this->yearNumber,
            $this->institute,
            $this->department,
            $this->group,
            $this->semester,
            $this->getSubject()->getId(),
            $this->groupType
        ];
    }
}
