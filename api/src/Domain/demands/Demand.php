<?php


namespace App\Domain\demands;


use App\Domain\users\User;
use Doctrine\Common\Collections\ArrayCollection;
use Rhumsaa\Uuid\Uuid;

class Demand
{
    /** @var Uuid */
    private $uuid;

    /** @var Subject */
    private $subject;

    /** @var ArrayCollection<LectureSet> */
    private $lectureSets;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->lectureSets = new ArrayCollection();
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
        if(!$this->lectureSets->contains($lectureSet)) {
            $this->lectureSets->add($lectureSet);
        }

        return $this;
    }

    public function changeLecturer(User $lecturer, int $lectureType)
    {
        foreach ($this->getLectureSets() as $lectureSet) {
            if($lectureSet->getLectureType() === $lectureType) {
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
}