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
use Demands\Domain\Update\AllocatedWeek;
use Demands\Domain\Update\DetailsToUpdate;
use Demands\Domain\Week;
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

    public function __construct(
        DemandRepository $demandRepository,
        StatusResolver $statusResolver,
        SubjectRepository $subjectRepository,
        GroupRepository $groupRepository
    )
    {
        $this->demandRepository = $demandRepository;
        $this->statusResolver = $statusResolver;
        $this->subjectRepository = $subjectRepository;
        $this->groupRepository = $groupRepository;
    }

    public function listAllForUser(User $user)
    {
        if ($user->isTeacher()) {
            return $this->demandRepository->listAllForUser($user);
        } else {
            return $this->demandRepository->listAllWithStatuses($this->statusResolver->resolveStatusesForDemandListing($user));
        }
    }

    /**
     * @param User $importedBy
     * @param StudyPlan[] $studyPlans
     * @return Demand[]
     */
    public function createDemandsFromStudyPlans(User $importedBy, array $studyPlans): array
    {
        $demands = [];
        foreach ($studyPlans as $studyPlan) {
            $demands[] = $this->createDemandFromStudyPlan($importedBy, $studyPlan);
        }

        return $demands;
    }

    private function createDemandFromStudyPlan(User $importedBy, StudyPlan $studyPlan): Demand
    {
        $subject = $this->subjectRepository->findByName($studyPlan->subjectName);
        if (!$subject) {
            $subject = new Subject($studyPlan->subjectName, $studyPlan->subjectShortName);
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
                $currentLectureSet->allocateWeek($currentAllocatedWeek);
            } else {
                $currentAllocatedWeek->setPlace($place);
            }
        }
    }
}