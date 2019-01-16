<?php

namespace App\Service;

use App\Entity\Demand;
use App\Repository\UserRepository;
use http\Exception\RuntimeException;

class LectureService
{
    private $userRepository;
    private $scheduleService;

    public function __construct(
        UserRepository $userRepository,
        ScheduleService $scheduleService
    ) {
        $this->userRepository = $userRepository;
        $this->scheduleService = $scheduleService;
    }

    public function updateLectures(Demand $demand, array $data) {
        foreach ($data as $lecture) {
            $this->updateLecture($demand, $lecture);
        }
    }

    private function updateLecture(Demand $demand, array $lectureArray)
    {
        $lecture = $demand->getLecture($lectureArray['id']);
        if(!$lecture) {
            throw new RuntimeException("Zajęcia o podanym id nie istnieją!");
        }
        $newLecturer = $this->userRepository->find($lectureArray['lecturer']['id']);

        $lecture->setComments($lectureArray['comments']);
        $lecture->setLecturer($newLecturer);
        $this->scheduleService->updateSchedules($lecture, $lectureArray);
//        $this->lectureRepository->persist($lecture); possible persist HERE! TODO
    }
}