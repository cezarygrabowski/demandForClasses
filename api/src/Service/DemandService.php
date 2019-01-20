<?php

namespace App\Service;

use App\Entity\Demand;
use App\Entity\Lecture;
use App\Entity\LectureType;
use App\Entity\Schedule;
use App\Entity\Subject;
use App\Repository\BuildingRepository;
use App\Repository\DemandRepository;
use App\Repository\LectureTypeRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\User;

class DemandService
{
    private $demandRepository;
    private $lectureService;
    private $buildingRepository;
    private $userRepository;
    private $lectureTypeRepository;
    private $subjectRepository;

    /**
     * DemandService constructor.
     * @param DemandRepository $demandRepository
     * @param LectureService $lectureService
     * @param BuildingRepository $buildingRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        DemandRepository $demandRepository,
        LectureService $lectureService,
        BuildingRepository $buildingRepository,
        UserRepository $userRepository,
        LectureTypeRepository $lectureTypeRepository,
        SubjectRepository $subjectRepository
    ) {
        $this->demandRepository = $demandRepository;
        $this->lectureService = $lectureService;
        $this->buildingRepository = $buildingRepository;
        $this->userRepository = $userRepository;
        $this->lectureTypeRepository = $lectureTypeRepository;
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * This method is used when importing schedules.
     * It generates demand that is partially filled.
     */
    public function generateDemand(array $row): Demand
    {
        if ($demand = $this->demandRepository->findDemandByImportData($row)) {
            $this->addLectureForDemand($demand, $row);
        } else {
            $demand = new Demand();
            $demand->setGroup($row[0]);
            $demand->setYearNumber($row[6]);
            $demand->setGroupType($row[7]);
            $demand->setSemester($row[8]);
            $demand->setDepartment($row[9]);
            $demand->setInstitute($row[10]);

            $subject = $this->subjectRepository->findOneBy(['name' => $row[1]]);
            $demand->setStatus(Demand::STATUS_UNTOUCHED);
            $demand->setSubject($subject);

            $this->addLectureForDemand($demand, $row);
        }

        return $demand;
    }

    public function generateDemands(array $data): void
    {
        /** @var Schedule $schedule */
        foreach ($data as $row) {
            $demand = $this->generateDemand($row);
            $this->demandRepository->getEntityManager()->persist($demand);
            $this->demandRepository->getEntityManager()->flush();
        }
    }

    public function updateDemand(Demand $demand, User $user, array $data)
    {
        //comments and lecturer
        $this->lectureService->updateLectures($demand, $data['lectures']);
        //check who updates demand(based on role)
        //if it is a teacher then update it accordingly to what was passed in the request
        $this->updateStatus($user, $demand);
    }

    public function findAll(): array
    {
        $demands = $this->demandRepository->findAll();

        $dtos = [];
        foreach ($demands as $demand) {
            $dtos[] = \App\DTO\Demand::fromDemand($demand);
        }
        return $dtos;
    }

    public function findAllBuildings()
    {
        $demands = $this->buildingRepository->findAll();

        return $demands;
    }

    private function updateStatus(User $user, Demand $demand)
    {
//        if($user)
    }

    private function addLectureForDemand(Demand $demand, array $row)
    {
        $lectureType = $this->lectureTypeRepository->findOneBy(['name' => $row[3]]);
        $lecture = new Lecture();
        $lecture->setHours($row[5]);
        $lecture->setLectureType($lectureType);
        $lecture->setDemand($demand);
        $demand->addLecture($lecture);
    }

    public function exportDemands()
    {
        $demands = $this->demandRepository->findAll();

        foreach ($demands as $demand) {
            $test = '';
        }
    }
}
