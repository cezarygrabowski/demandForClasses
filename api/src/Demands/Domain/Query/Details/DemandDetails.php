<?php


namespace Demands\Domain\Query\Details;


use App\Demands\Domain\Query\Details\LectureSet;
use Demands\Domain\Demand;

class DemandDetails
{
    public $subjectName;
    public $department;
    public $institute;
    public $semester;
    public $schoolYear;
    public $statusName;
    public $groupName;
    public $groupType;
    public $uuid;
    /**
     * @var LectureSet[]
     */
    public $lectureSets;
    public static function fromDemand(Demand $demandEntity)
    {
        $demand = new self();

        $demand->subjectName = $demandEntity->getSubject()->getName();
        $demand->department = $demandEntity->getDepartment();
        $demand->institute = $demandEntity->getInstitute();
        $demand->semester = $demandEntity->getSemester();
        $demand->schoolYear = $demandEntity->getSchoolYear();
        $demand->statusName = $demandEntity->getTranslatedStatus();
        $demand->uuid = $demandEntity->getUuid();
        $demand->groupType = $demandEntity->getGroup()->getTranslatedType();
        $demand->groupName = $demandEntity->getGroup()->getName();

        foreach ($demandEntity->getLectureSets() as $lectureSet) {
            $lectureSetDetails = new LectureSet();
            $lectureSetDetails->uuid = $lectureSet->getUuid();
            $lectureSetDetails->type = $lectureSet->getTranslatedLectureType();

            $entityLecturer = $lectureSet->getLecturer();
            if($entityLecturer) {
                $lecturer = new Lecturer();
                $lecturer->uuid = $entityLecturer->getUuid();
                $lecturer->username = $entityLecturer->getUsername();

                $lectureSetDetails->lecturer = $lecturer;
            }
            $lectureSetDetails->hoursToDistribute = $lectureSet->getHoursToDistribute();
            $lectureSetDetails->notes = $lectureSet->getNotes();

            foreach ($lectureSet->getAllocatedWeeks() as $allocatedWeek) {
                $place = $allocatedWeek->getPlace();

                $lectureSetDetails->allocatedWeeks[$allocatedWeek->getNumber()] = [
                    'allocatedHours' => $allocatedWeek->getAllocatedHours(),
                    'room' => $place ? $place->getRoom() : null,
                    'building' => $place ? $place->getBuilding() : null
                ];
            }

            $demand->lectureSets[] = $lectureSetDetails;
        }

        return $demand;
    }
}
