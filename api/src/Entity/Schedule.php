<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

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
     * @OneToOne(targetEntity="Building")
     */
    private $building;

    /**
     * @ORM\ManyToOne(targetEntity="Lecture", fetch="EAGER", inversedBy="schedules")
     */
    private $lecture;

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
}