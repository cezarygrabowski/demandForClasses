<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Table(name="demands")
 * @ORM\Entity
 */
class Demand
{
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $group;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $semester;

    /**
     * @ORM\ManyToOne(targetEntity="Subject")
     */
    private $subject;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $groupType;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $totalHours;

    /**
     * @OneToMany(targetEntity="Lecture", mappedBy="demand", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $lectures;

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getTotalHours()
    {
        return $this->totalHours;
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

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
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

    }
}