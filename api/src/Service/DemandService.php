<?php

namespace App\Service;

use App\Entity\Building;
use App\Entity\Demand;
use App\Entity\Schedule;
use App\Repository\BuildingRepository;
use App\Repository\DemandRepository;
use Symfony\Component\Security\Core\User\User;

class DemandService
{
    private $demandRepository;
    private $lectureService;
    private $buildingRepository;

    public function __construct(
        DemandRepository $demandRepository,
        LectureService $lectureService,
        BuildingRepository $buildingRepository
    ) {
        $this->demandRepository = $demandRepository;
        $this->lectureService = $lectureService;
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * This method is used when importing schedules.
     * It generates demand that is partially filled.
     */
    public function generateDemand(array $row): Demand
    {
        $demand = new Demand();
        $demand->setGroup($row[0]);
        $demand->setSubject($row[1]);
        $demand->setShortenedSubjectName($row[2]);
        $demand->setYearNumber($row[6]);
        $demand->setGroupType($row[7]);
        $demand->setSemester($row[8]);
        $demand->setDepartment($row[9]);
        $demand->setInstitute($row[10]);

        $demand->setLectureTypes($this->findLectureTypesForDemand($row));
        return $demand;
    }

    public function generateDemands(array $data): void
    {
        /** @var Schedule $schedule */
        foreach ($data as $row) {
            $lectureTypes = $this->findLectureTypesForGivenRow($row, $data);
            $demand = $this->generateDemand($row, $lectureTypes);
            $this->em->persist($demand);
        }

        $this->em->flush();
    }

    public function updateDemand(Demand $demand, User $user, array $data)
    {
        //comments and lecturer
        $this->lectureService->updateLectures($demand, $data['lectures']);
        //check who updates demand(based on role)
        //if it is a teacher then update it accordingly to what was passed in the request
        $this->updateStatus($user, $demand);
    }

    private function findLectureTypesForDemand(array $row, array &$data)
    {

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
}