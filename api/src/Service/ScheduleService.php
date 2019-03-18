<?php

namespace App\Service;

use App\DTO\ScheduleRow;
use App\Entity\Lecture;
use App\Entity\LectureType;
use App\Entity\Schedule;
use App\Entity\Subject;
use App\Repository\BuildingRepository;
use App\Repository\LectureTypeRepository;
use App\Repository\RoomRepository;
use App\Repository\ScheduleRepository;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;

class ScheduleService
{
    /** @var ScheduleRepository  */
    private $scheduleRepository;
    /** @var BuildingRepository  */
    private $buildingRepository;
    /** @var RoomRepository  */
    private $roomRepository;
    /** @var DemandService  */
    private $demandService;
    /** @var SubjectRepository  */
    private $subjectRepository;
    /** @var EntityManagerInterface  */
    private $em;
    /** @var LectureTypeRepository  */
    private $lectureTypeRepository;

    public function __construct(
        ScheduleRepository $scheduleRepository,
        BuildingRepository $buildingRepository,
        RoomRepository $roomRepository,
        DemandService $demandService,
        SubjectRepository $subjectRepository,
        EntityManagerInterface $entityManager,
        LectureTypeRepository $lectureTypeRepository
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->buildingRepository = $buildingRepository;
        $this->roomRepository = $roomRepository;
        $this->demandService = $demandService;
        $this->subjectRepository = $subjectRepository;
        $this->em = $entityManager;
        $this->lectureTypeRepository = $lectureTypeRepository;
    }

    public function updateSchedules(Lecture $lecture, array $lectureArray)
    {
        foreach ($lectureArray['schedules'] as $scheduleArray) {
            $this->updateSchedule($lecture, $scheduleArray);
        }
    }

    private function updateSchedule(Lecture $lecture, $scheduleArray)
    {
        $schedule = $lecture->getSchedule($scheduleArray['id']);
        if(isset($scheduleArray['building']['id'])) {
            $building = $this->buildingRepository->find($scheduleArray['building']['id']);
        } else {
            $building = $this->buildingRepository->findOneBy(['name' => $scheduleArray['building']]);
        }
        $room = $this->roomRepository->findOneBy(['name' => $scheduleArray['room']]);

        if ($schedule) {
            $schedule->setSuggestedHours($scheduleArray['suggestedHours']);
            $schedule->setBuilding($building);
            $schedule->setRoom($room);
            $this->scheduleRepository->em->persist($schedule);
        } else {
            if ($scheduleArray['id']) {
                throw new RuntimeException("Something's horribly wrong");
            }

            $schedule = new Schedule();
            $schedule->setRoom($room);
            $schedule->setLecture($lecture);
            $schedule->setBuilding($building);
            $schedule->setSuggestedHours($scheduleArray['suggestedHours']);
            $schedule->setWeekNumber($scheduleArray['weekNumber']);
            $lecture->addSchedule($schedule);
        }
    }

    /**
     * @param ScheduleRow[] $schedules
     */
    public function importSchedules(array $schedules): void
    {
        $this->generateSubjects($schedules);
        $this->generateLectureTypes($schedules);
        $this->demandService->generateDemands($schedules);
    }

    /**
     * @param ScheduleRow[] $schedules
     */
    public function generateSubjects(array $schedules)
    {
        $subjects = [];

        foreach ($schedules as $schedule) {
            $subjects[$schedule->shortenedSubject] = $schedule->subject;
        }

        $subjects = array_unique($subjects);
        foreach ($subjects as $key => $name) {
            if(!$this->subjectRepository->findOneBy(['name' => $name])) {
                $obj = new Subject();
                $obj->setName($name);
                $obj->setShortenedName($key);
                $this->em ->persist($obj);
            }
        }

        $this->em->flush();
    }

    /**
     * @param ScheduleRow[] $schedules
     */
    private function generateLectureTypes(array $schedules)
    {
        $lectureTypes = [];

        foreach ($schedules as $scheduleRow) {
            $lectureTypes[] = $scheduleRow->lectureType;
        }

        $lectureTypes = array_unique($lectureTypes);
        foreach ($lectureTypes as $key => $name) {
            if(!$this->lectureTypeRepository->findOneBy(['name' => $name])) {
                $obj = new LectureType();
                $obj->setName($name);
                $this->em->persist($obj);
            }
        }
        $this->em->flush();
    }
}
