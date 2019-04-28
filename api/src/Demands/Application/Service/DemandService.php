<?php


namespace Demands\Application\Service;


use DateTime;
use Demands\Domain\Demand;
use Demands\Domain\Group;
use Demands\Domain\Import\StudyPlan\StudyPlan;
use Demands\Domain\LectureSet;
use Demands\Domain\Repository\DemandRepository;
use Demands\Domain\Repository\GroupRepository;
use Demands\Domain\Repository\PlaceRepository;
use Demands\Domain\Repository\SubjectRepository;
use Demands\Domain\StatusResolver;
use Demands\Domain\Subject;
use Demands\Domain\Update\DetailsToUpdate;
use Demands\Domain\Week;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Exception;
use Users\Domain\User;

class DemandService
{
    /**
     * @var DemandRepository
     */
    private $demandRepository;

    /**
     * @var StatusResolver
     */
    private $statusResolver;
    /**
     * @var SubjectRepository
     */
    private $subjectRepository;
    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        DemandRepository $demandRepository,
        StatusResolver $statusResolver,
        SubjectRepository $subjectRepository,
        GroupRepository $groupRepository,
        EntityManagerInterface $entityManager,
        PlaceRepository $placeRepository
    ) {
        $this->demandRepository = $demandRepository;
        $this->statusResolver = $statusResolver;
        $this->subjectRepository = $subjectRepository;
        $this->groupRepository = $groupRepository;
        $this->entityManager = $entityManager;
        $this->placeRepository = $placeRepository;
    }

    public function listAllForUser(User $user)
    {
        if ($user->isTeacher()) {
            $demands = $this->demandRepository->listAllForUser($user);
        } else {
            //TODO DO NOT FORGET ABOUT THAT
//            $demands =  $this->demandRepository->listAllWithStatuses($this->statusResolver->resolveStatusesForDemandListing($user));
            $demands =  $this->demandRepository->listAllWithStatuses([6]);
        }

        $demandDtos = [];
        foreach ($demands as $demand) {
            $demandDtos[] = \App\Demands\Domain\Query\Demand::fromDemand($demand);
        }

        return $demandDtos;
    }

    /**
     * @param User $importedBy
     * @param StudyPlan[] $studyPlans
     * @return Demand[]
     */
    public function createDemandsFromStudyPlans(User $importedBy, array $studyPlans): array
    {
        $demands = [];
        $subjects = []; //Help array to assure that no duplicates will be made
        foreach ($studyPlans as $studyPlan) {
            $demand = $this->createDemandFromStudyPlan($importedBy, $studyPlan, $subjects);
            $demands[] = $demand;
            $this->entityManager->persist($demand);
        }

        return $demands;
    }

    private function createDemandFromStudyPlan(User $importedBy, StudyPlan $studyPlan, array &$subjects): Demand
    {
        $subject = $this->subjectRepository->findByName($studyPlan->subjectName);

        if(array_key_exists($studyPlan->subjectName, $subjects)) {
            $subject = $subjects[$studyPlan->subjectName];
        } elseif (!$subject) {
            $subject = new Subject($studyPlan->subjectName, $studyPlan->subjectShortName);
            $subjects[$studyPlan->subjectName] = $subject;
        }

        $group = $this->groupRepository->find($studyPlan->groupName);
        if (!$group) {
            $group = new Group($studyPlan->groupName, $studyPlan->groupType);
        }

        $demand = new Demand();
        $demand->setImportedBy($importedBy)
            ->setSubject($subject)
            ->setStatus(Demand::STATUS_UNTOUCHED)
            ->setImportedAt(new DateTime())
            ->setSemester($studyPlan->semester)
            ->setSchoolYear($studyPlan->schoolYear)
            ->setGroup($group)
            ->setInstitute($studyPlan->institute)
            ->setDepartment($studyPlan->department);

        foreach ($studyPlan->lectureSets as $lectureSetDto) {
            $lectureSet = new LectureSet($lectureSetDto->type);
            $lectureSet->setHoursToDistribute($lectureSetDto->hours);
            $lectureSet->setDemand($demand);
            $demand->addLectureSet($lectureSet);
        }

        return $demand;
    }

    public function update(Demand $demand, DetailsToUpdate $detailsToUpdate)
    {
        foreach ($detailsToUpdate->lectureSets as $lectureSet) {
            $currentLectureSet = $demand->getLectureSet($lectureSet->type);
            $this->updateLectureSet($currentLectureSet, $lectureSet);
        }

        $this->entityManager->persist($demand);
        $this->entityManager->flush();
    }

    /**
     * @param LectureSet $currentLectureSet
     * @param \Demands\Domain\Update\LectureSet $lectureSet
     * @throws Exception
     */
    private function updateLectureSet(LectureSet $currentLectureSet, \Demands\Domain\Update\LectureSet $lectureSet)
    {
        foreach ($lectureSet->allocatedWeeks as $allocatedWeek) {
            $place = $this->placeRepository->findOneByBuildingAndRoom($allocatedWeek->building, $allocatedWeek->room);
            if(!$place) {
                throw new DomainException("Nie ma takiego miejsca!");
            }

            if ($allocatedWeek->building === '' || $allocatedWeek->room === '') {
                throw new DomainException("Budynek lub/i pole nie jest uzupelnione");
            }

            $currentAllocatedWeek = $currentLectureSet->getAllocatedWeek($allocatedWeek->weekNumber);
            if (!$currentAllocatedWeek) {
                $currentAllocatedWeek = new Week($allocatedWeek->weekNumber, $allocatedWeek->hours, $place);
                $currentAllocatedWeek->setLectureSet($currentLectureSet);
                $currentLectureSet->allocateWeek($currentAllocatedWeek);
                $currentLectureSet->addNotes($lectureSet->notes);
            } else {
                $currentAllocatedWeek->setPlace($place);
                $currentAllocatedWeek->setAllocatedHours($allocatedWeek->hours);
            }

            if($currentLectureSet->getUndistributedHours() < 0) {
                throw new DomainException(
                    "Zaalokowałeś za dużo godzin! " . $currentLectureSet->getDistributedHours() . ' z dostępnych: ' . $currentLectureSet->getHoursToDistribute()
                );
            }
        }
    }
}
