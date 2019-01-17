<?php

namespace App\Service;

use App\Entity\Lecture;
use App\Entity\Schedule;
use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use App\Repository\ScheduleRepository;
use http\Exception\RuntimeException;

class ScheduleService
{
    private $scheduleRepository;
    private $buildingRepository;
    private $roomRepository;

    public function __construct(
        ScheduleRepository $scheduleRepository,
        BuildingRepository $buildingRepository,
        RoomRepository $roomRepository
    )
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->buildingRepository = $buildingRepository;
        $this->roomRepository = $roomRepository;
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
}
