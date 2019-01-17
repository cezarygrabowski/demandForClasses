<?php

namespace App\Service;

use App\Entity\Demand;
use App\Repository\LectureRepository;
use App\Repository\UserRepository;
use http\Exception\RuntimeException;

class LectureService
{
    private $userRepository;
    private $scheduleService;
    private $lectureRepository;
    public function __construct(
        UserRepository $userRepository,
        ScheduleService $scheduleService,
        LectureRepository $lectureRepository
    ) {
        $this->userRepository = $userRepository;
        $this->scheduleService = $scheduleService;
        $this->lectureRepository = $lectureRepository;
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

        //Very dirty!
        if(isset($lectureArray['lecturer']['id'])){
            $newLecturer = $this->userRepository->find($lectureArray['lecturer']['id']);
        } else {
            $newLecturer = $this->userRepository->findOneBy(['username' => $lectureArray['lecturer']]);
        }

        $lecture->setComments($lectureArray['comments']);
        $lecture->setLecturer($newLecturer);
        $this->scheduleService->updateSchedules($lecture, $lectureArray);
    }
}
