<?php

namespace App\Service;

use App\Domain\ScheduleReader;
use App\DTO\ScheduleRow;
use App\Entity\Demand;
use App\Entity\Lecture;
use App\Entity\LectureType;
use App\Entity\User;
use App\Repository\BuildingRepository;
use App\Repository\DemandRepository;
use App\Repository\LectureRepository;
use App\Repository\LectureTypeRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class DemandService
{
    private $demandRepository;
    private $scheduleService;
    private $buildingRepository;
    private $userRepository;
    private $lectureTypeRepository;
    private $subjectRepository;

    /**
     * @var LectureRepository
     */
    private $lectureRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        DemandRepository $demandRepository,
        ScheduleService $scheduleService,
        BuildingRepository $buildingRepository,
        UserRepository $userRepository,
        LectureTypeRepository $lectureTypeRepository,
        SubjectRepository $subjectRepository,
        LectureRepository $lectureRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->demandRepository = $demandRepository;
        $this->scheduleService = $scheduleService;
        $this->buildingRepository = $buildingRepository;
        $this->userRepository = $userRepository;
        $this->lectureTypeRepository = $lectureTypeRepository;
        $this->subjectRepository = $subjectRepository;
        $this->lectureRepository = $lectureRepository;
        $this->entityManager = $entityManager;
    }

    public function updateDemand(Demand $demand, User $user, array $data)
    {
        $this->updateStatus($user, $demand);
        $this->scheduleService->updateLectures($demand, $data['lectures']);

        $this->entityManager->flush();
    }

    public function findAll(User $user): array
    {
        if ($user->isAdmin()) {
            $demands = $this->demandRepository->findAll();
        }

        if ($user->isTeacher()) {
            $demands = $this->demandRepository->findAllForNauczyciel($user);
        }

        if ($user->isDepartmentManager()) {
            $demands = $this->demandRepository->findAllForKierownikZakladu();
        }

        if ($user->isInstituteDirector()) {
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

    private function updateStatus(User $user, Demand $demand)
    {
        if ($user->isTeacher()) {
            $demand->setStatus(Demand::STATUS_ACCEPTED_BY_TEACHER);
        }

        if ($user->isDepartmentManager()) {
            $demand->setStatus(Demand::STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER);
        }

        if ($user->isInstituteDirector()) {
            $demand->setStatus(Demand::STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER);
            $demand->setStatus(Demand::STATUS_ACCEPTED_BY_DZIEKAN);
        }
    }

    public function cancelDemand(Demand $demand, ?User $user)
    {
        if (!$user) {
            throw new Exception("Użytkownik nie jest zalogowany!");
        }

        /** @var Lecture $lecture */
        foreach ($demand->getLectures() as $lecture) {
            if ($lecture->getLecturer() && $lecture->getLecturer()->getId() === $user->getId()) {
                $lecture->setLecturer(null);
                $this->entityManager->persist($lecture);
            }
        }
        $demand->setStatus(Demand::STATUS_UNTOUCHED);
        $this->entityManager->flush();
    }
}
