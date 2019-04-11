<?php

namespace App\Domain;

use App\DTO\ScheduleRow;
use App\Entity\Demand;
use App\Entity\Lecture;
use App\Entity\LectureType;
use App\Repository\DemandRepository;
use App\Repository\LectureTypeRepository;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class DemandGenerator
{
    private $demandRepository;
    private $lectureTypeRepository;
    private $subjectRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        DemandRepository $demandRepository,
        LectureTypeRepository $lectureTypeRepository,
        SubjectRepository $subjectRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->demandRepository = $demandRepository;
        $this->lectureTypeRepository = $lectureTypeRepository;
        $this->subjectRepository = $subjectRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * This method is used when importing schedules.
     * It generates demand that is partially filled.
     * @param ScheduleRow $scheduleRow
     * @return Demand
     */
    public function generateDemand(ScheduleRow $scheduleRow): Demand
    {
        if ($demand = $this->demandRepository->findDemandByScheduleData($scheduleRow)) {
            $this->addLectureForDemand(
                $demand,
                $scheduleRow->hours,
                $this->lectureTypeRepository->findOneBy(['name' => $scheduleRow->lectureType])
            );
        } else {
            $demand = new Demand();
            $demand->setGroup($scheduleRow->group);
            $demand->setYearNumber($scheduleRow->yearNumber);
            $demand->setGroupType($scheduleRow->groupType);
            $demand->setSemester($scheduleRow->semester);
            $demand->setDepartment($scheduleRow->department);
            $demand->setInstitute($scheduleRow->institute);

            $subject = $this->subjectRepository->findOneBy(['name' => $scheduleRow->subject]);
            $demand->setStatus(Demand::STATUS_UNTOUCHED);
            $demand->setSubject($subject);

            $this->addLectureForDemand(
                $demand,
                $scheduleRow->hours,
                $this->lectureTypeRepository->findOneBy(['name' => $scheduleRow->lectureType])
            );
        }

        return $demand;
    }

    /**
     * @param ScheduleRow[] $schedules
     */
    public function generateDemands(array $schedules): void
    {
        foreach ($schedules as $row) {
            $demand = $this->generateDemand($row);
            $this->entityManager->persist($demand);
            $this->entityManager->flush();
        }
    }

    private function addLectureForDemand(Demand $demand, string $hours, LectureType $lectureType)
    {
        $lecture = new Lecture();
        $lecture->setHours($hours);
        $lecture->setLectureType($lectureType);
        $lecture->setDemand($demand);
        $demand->addLecture($lecture);
    }
}