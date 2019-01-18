<?php

namespace App\Service;

use App\Entity\LectureType;
use App\Entity\Subject;
use App\Repository\LectureTypeRepository;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ImportExportService
{
    private $demandService;
    private $entityManager;
    private $userService;
    private $lectureTypeRepository;
    private $subjectRepository;

    public function __construct(
        DemandService $demandService,
        EntityManagerInterface $entityManager,
        UserService $userService,
        LectureTypeRepository $lectureTypeRepository,
        SubjectRepository $subjectRepository
    ) {
        $this->demandService = $demandService;
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->lectureTypeRepository = $lectureTypeRepository;
        $this->subjectRepository = $subjectRepository;
    }

    public function importSchedules(array $schedules): void
    {
        $this->generateSubjects($schedules);
        $this->generateLectureTypes($schedules);
        $this->demandService->generateDemands($schedules);
    }

    public function exportDemands($data)
    {

    }

    public function importUsers($data)
    {
        $this->userService->generateUsers($data);
    }

    private function generateSubjects(array $schedules)
    {
        $subjects = [];

        foreach ($schedules as $schedule) {
            $subjects[$schedule[2]] = $schedule[1];
        }

        $subjects = array_unique($subjects);
        $em = $this->subjectRepository->getEntityManager();
        foreach ($subjects as $key => $name) {
            if(!$this->subjectRepository->findOneBy(['name' => $name])) {
                $obj = new Subject();
                $obj->setName($name);
                $obj->setShortenedName($key);
                $em->persist($obj);
            }
        }
        $em->flush();
    }

    private function generateLectureTypes(array $schedules)
    {
        $lectureTypes = [];

        foreach ($schedules as $schedule) {
            $lectureTypes[] = $schedule[3];
        }

        $lectureTypes = array_unique($lectureTypes);
        $em = $this->subjectRepository->getEntityManager();
        foreach ($lectureTypes as $key => $name) {
            if(!$this->lectureTypeRepository->findOneBy(['name' => $name])) {
                $obj = new LectureType();
                $obj->setName($name);
                $em->persist($obj);
            }
        }
        $em->flush();
    }
}