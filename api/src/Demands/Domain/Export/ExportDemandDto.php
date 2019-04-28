<?php


namespace Demands\Domain\Export;


use Demands\Domain\Demand;
use Demands\Domain\Week;

class ExportDemandDto
{
    public $lectureType;
    public $demandUuid;
    public $lecturer;
    public $subjectName;
    public $schoolYear;
    public $group;
    public $groupType;
    public $semester;
    public $institute;
    public $department;
    public $hours;
    /** @var Week */
    public $allocatedWeeks;

    /**
     * @return ExportDemandDto[]
     * @param Demand[] $demands
     */
    public static function prepareDemands(array $demands): array
    {
        $exportDemands = [];

        foreach ($demands as $demand) {
            $exportDemands = array_merge($exportDemands, self::prepareDemand($demand));
        }

        return $exportDemands;
    }

    /**
     * Every lectureSet has separate row
     * @param Demand $demand
     * @return array
     */
    private static function prepareDemand(Demand $demand): array
    {
        $exportDemands = [];
        foreach ($demand->getLectureSets() as $lectureSet) {
            $exportDemandDto = new self();
            $exportDemandDto->lectureType = $lectureSet->getLectureType();
            $exportDemandDto->demandUuid = $demand->getUuid();
            if($lectureSet->getLecturer()) {
                $exportDemandDto->lecturer = $lectureSet->getLecturer()->getUsername();
            } else {
                $exportDemandDto->lecturer = '';
            }
            $exportDemandDto->subjectName = $demand->getSubject()->getName();
            $exportDemandDto->schoolYear = $demand->getSchoolYear();
            $exportDemandDto->group = $demand->getGroup()->getName();
            $exportDemandDto->groupType = $demand->getGroup()->getType();
            $exportDemandDto->semester = $demand->getSemester();
            $exportDemandDto->institute = $demand->getInstitute();
            $exportDemandDto->department = $demand->getDepartment();
            $exportDemandDto->hours = $lectureSet->getHoursToDistribute();
            $exportDemandDto->allocatedWeeks = $lectureSet->getAllocatedWeeks();

            $exportDemands[] = $exportDemandDto;
        }

        return $exportDemands;
    }

    public function getWeeks(): array
    {
        $weeks = [];
        /** @var Week $allocatedWeek */
        foreach ($this->allocatedWeeks as $allocatedWeek){
            $weeks[] = $allocatedWeek->getAllocatedHours();
            $weeks[] = $allocatedWeek->getPlace()->getBuilding();
            $weeks[] = $allocatedWeek->getPlace()->getRoom();
        }

        return $weeks;
    }
}
