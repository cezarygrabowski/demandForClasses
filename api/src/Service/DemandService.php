<?php

namespace App\Service;

use App\Entity\Demand;
use App\Entity\Lecture;
use App\Entity\LectureType;
use App\Entity\Schedule;
use App\Entity\Subject;
use App\Repository\BuildingRepository;
use App\Repository\DemandRepository;
use App\Repository\LectureRepository;
use App\Repository\LectureTypeRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Symfony\Component\Config\Definition\Exception\Exception;
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
     * @var LectureRepository
     */
    private $lectureRepository;

    /**
     * DemandService constructor.
     * @param DemandRepository $demandRepository
     * @param LectureService $lectureService
     * @param BuildingRepository $buildingRepository
     * @param UserRepository $userRepository
     * @param LectureTypeRepository $lectureTypeRepository
     * @param SubjectRepository $subjectRepository
     * @param LectureRepository $lectureRepository
     */
    public function __construct(
        DemandRepository $demandRepository,
        LectureService $lectureService,
        BuildingRepository $buildingRepository,
        UserRepository $userRepository,
        LectureTypeRepository $lectureTypeRepository,
        SubjectRepository $subjectRepository,
        LectureRepository $lectureRepository
    ) {
        $this->demandRepository = $demandRepository;
        $this->lectureService = $lectureService;
        $this->buildingRepository = $buildingRepository;
        $this->userRepository = $userRepository;
        $this->lectureTypeRepository = $lectureTypeRepository;
        $this->subjectRepository = $subjectRepository;
        $this->lectureRepository = $lectureRepository;
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

    public function updateDemand(Demand $demand, \App\Entity\User $user, array $data)
    {
        $this->updateStatus($user, $demand);
        $this->lectureService->updateLectures($demand, $data['lectures']);
    }

    public function findAll(\App\Entity\User $user): array
    {
        if($user->isAdmin()) {
            $demands = $this->demandRepository->findAll();
        }

        if($user->isNauczyciel()) {
            $demands = $this->demandRepository->findAllForNauczyciel($user);
        }

        if($user->isKierownikZakladu()) {
            $demands = $this->demandRepository->findAllForKierownikZakladu();
        }

        if($user->isDyrektorInstytutu()) {
            $demands = $this->demandRepository->findAllForDyrektorInstytutu();
        }

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

    private function updateStatus(\App\Entity\User $user, Demand $demand)
    {
        if($user->isNauczyciel()) {
            $demand->setStatus(Demand::STATUS_ACCEPTED_BY_NAUCZYCIEL);
        }

        if($user->isKierownikZakladu()) {
            $demand->setStatus(Demand::STATUS_ASSIGNED_BY_KIEROWNIK_ZAKLADU);
        }

        if($user->isDyrektorInstytutu()) {
            $demand->setStatus(Demand::STATUS_ASSIGNED_BY_KIEROWNIK_ZAKLADU);
            $demand->setStatus(Demand::STATUS_ACCEPTED_BY_DZIEKAN);
        }
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

    public function cancelDemand(Demand $demand, ?\App\Entity\User $user)
    {
        if(!$user) {
            throw new Exception("Użytkownik nie jest zalogowany!");
        }

        /** @var Lecture $lecture */
        foreach ($demand->getLectures() as $lecture) {
            if($lecture->getLecturer() && $lecture->getLecturer()->getId() === $user->getId()) {
                $lecture->setLecturer(null);
                $this->lectureRepository->getEntityManager()->persist($lecture);
            }
        }
        $demand->setStatus(Demand::STATUS_UNTOUCHED);
        $this->lectureRepository->getEntityManager()->flush();
    }
}
