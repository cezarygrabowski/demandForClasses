<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Table(name="schedules")
 * @ORM\Entity
 */
class Schedule
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $weekNumber;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $suggestedHours;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $building;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $room;

    /**
     * @ManyToOne(targetEntity="LectureType", cascade={"all"}, fetch="EAGER", inversedBy="id")
     * @ORM\Column(type="string")
     */
    private $lectureType;

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
    public function getWeekNumber()
    {
        return $this->weekNumber;
    }

    /**
     * @param mixed $weekNumber
     */
    public function setWeekNumber($weekNumber): void
    {
        $this->weekNumber = $weekNumber;
    }

    /**
     * @return mixed
     */
    public function getSuggestedHours()
    {
        return $this->suggestedHours;
    }

    /**
     * @param mixed $suggestedHours
     */
    public function setSuggestedHours($suggestedHours): void
    {
        $this->suggestedHours = $suggestedHours;
    }

    /**
     * @return mixed
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param mixed $building
     */
    public function setBuilding($building): void
    {
        $this->building = $building;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room): void
    {
        $this->room = $room;
    }

    /**
     * @return mixed
     */
    public function getLectureType()
    {
        return $this->lectureType;
    }

    /**
     * @param mixed $lectureType
     */
    public function setLectureType($lectureType): void
    {
        $this->lectureType = $lectureType;
    }


}