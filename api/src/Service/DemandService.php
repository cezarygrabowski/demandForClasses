<?php

namespace App\Service;

use App\Entity\Demand;
use App\Entity\Schedule;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class DemandService
{
//    const GROUP = 0;
//    const GROUP = 1;
//    const GROUP = 2;
//    const GROUP = 3;
//    const GROUP = 4;
//    const GROUP = 5;
//    const GROUP = 6;
//    const GROUP = 7;
//    const GROUP = 8;
//    const GROUP = 9;
//    const GROUP = 10;

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
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
        //check who updates demand(based on role)
        //if it is a teacher then update it accordingly to what was passed in the request
    }

    private function findLectureTypesForDemand(array $row, array &$data)
    {

    }

    public function findAll()
    {
        $demands = $this->em->getRepository(Demand::class)->findAll();

        return $demands;
    }
}