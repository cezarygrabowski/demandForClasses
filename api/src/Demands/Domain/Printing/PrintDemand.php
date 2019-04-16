<?php


namespace Demands\Domain\Printing;


use Demands\Domain\Demand;

class PrintDemand
{
    /**
     * @var array
     */
    public $allocatedWeeks;
    /**
     * @var string
     */
    public $groupName;
    /**
     * @var int
     */
    public $groupType;
    /**
     * @var string
     */
    public $subjectName;
    /**
     * @var string
     */
    public $subjectShortName;
    /**
     * @var string
     */
    public $yearNumber;
    /**
     * @var string
     */
    public $semester;
    /**
     * @var string
     */
    public $department;
    /**
     * @var string
     */
    public $institute;

    public static function create(Demand $demand): PrintDemand
    {
        $printDemand = new self();

        $printDemand->groupName = $demand->getGroup()->getName();
        $printDemand->groupType = $demand->getGroup()->getTranslatedType();
        $printDemand->subjectName = $demand->getSubject()->getName();
        $printDemand->subjectShortName = $demand->getSubject()->getShortName();
        $printDemand->yearNumber = $demand->getSchoolYear();
        $printDemand->semester = $demand->getSemester();
        $printDemand->department = $demand->getDepartment();
        $printDemand->institute = $demand->getInstitute();

        foreach ($demand->getLectureSets() as $lectureSet) {
            foreach ($lectureSet->getAllocatedWeeks() as $allocatedWeek) {
                $printDemand->allocatedWeeks[$lectureSet->getTranslatedLectureType()][$allocatedWeek->getNumber()] = [
                    'hours' => $allocatedWeek->getAllocatedHours(),
                    'building' => $allocatedWeek->getPlace()->getBuilding(),
                    'room' => $allocatedWeek->getPlace()->getRoom()
                ];
            }
        }

        return $printDemand;
    }
}