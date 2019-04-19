<?php


namespace App\Demands\Domain\Query;


class Demand
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

    public static function fromDemand(\Demands\Domain\Demand $demandEntity)
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

        return $demand;
    }
}
