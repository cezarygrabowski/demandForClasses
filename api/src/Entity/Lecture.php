<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Table(name="lectures")
 * @ORM\Entity
 */
class Lecture
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Demand", cascade={"all"}, fetch="EAGER")
     */
    private $demand;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $hours;

    /**
     * @ManyToOne(targetEntity="User", cascade={"all"}, fetch="EAGER")
     */
    private $lecturer;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $comments;

    /**
     * @OneToMany(targetEntity="Schedule", mappedBy="lecture", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     * PersistentCollection
     */
    private $schedules;

    /**
     * @ORM\ManyToOne(targetEntity="LectureType")
     */
    private $lectureType;

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
    public function getDemand()
    {
        return $this->demand;
    }

    /**
     * @param mixed $demand
     */
    public function setDemand($demand): void
    {
        $this->demand = $demand;
    }

    /**
     * @return mixed
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @param mixed $hours
     */
    public function setHours($hours): void
    {
        $this->hours = $hours;
    }

    /**
     * @return mixed
     */
    public function getLecturer(): ?User
    {
        return $this->lecturer;
    }

    /**
     * @param mixed $lecturer
     */
    public function setLecturer($lecturer): void
    {
        $this->lecturer = $lecturer;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getSchedules(): PersistentCollection
    {
        return $this->schedules;
    }

    public function getSchedule(?int $id): Schedule
    {
        foreach ($this->schedules as $schedule) {
            if ($schedule->getId() === $id) {
                return $schedule;
            }
        }

        return null;
    }

    /**
     * @param mixed $schedules
     */
    public function setSchedules($schedules): void
    {
        $this->schedules = $schedules;
    }

    public function addSchedule(Schedule $schedule)
    {
        if (!$this->getSchedules()->contains($schedule)) {
            $this->getSchedules()->add($schedule);
        }
    }

}