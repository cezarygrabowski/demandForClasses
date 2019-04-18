<?php


namespace Demands\Domain\Import\StudyPlan;


use Demands\Domain\Group;
use Demands\Domain\Import\StudyPlan\Csv\CsvPositions;

class StudyPlan
{
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
     * @var LectureSet
     */
    public $lectureSets;
    /**
     * @var string
     */
    public $institute;
    /**
     * @var string
     */
    public $department;
    /**
     * @var string
     */
    public $schoolYear;
    /**
     * @var string
     */
    public $semester;

    public static function create(array $row, CsvPositions $positions): self
    {
        $scheduleRow = new self();
        $scheduleRow->groupName = $row[$positions::GROUP];
        $scheduleRow->groupType = Group::GROUP_TYPE_STRING_TO_INT[$row[$positions::GROUP_TYPE]];
        $scheduleRow->subjectName = $row[$positions::SUBJECT_NAME];
        $scheduleRow->subjectShortName = $row[$positions::SUBJECT_SHORT_NAME];
        $scheduleRow->schoolYear = $row[$positions::YEAR_NUMBER];
        $scheduleRow->semester = $row[$positions::SEMESTER];
        $scheduleRow->department = $row[$positions::DEPARTMENT];
        $scheduleRow->institute = $row[$positions::INSTITUTE];

        $lectureTypes = explode(';', $row[$positions::LECTURE_TYPES]);
        $hours = explode(';', $row[$positions::LECTURE_HOURS]);
        $lectureSets = [];
        foreach ($lectureTypes as $key => $value) {
            $lectureSets[] = new LectureSet(\Demands\Domain\LectureSet::LECTURE_TYPES_STRING_TO_INT[$value], $hours[$key]);
        }
        $scheduleRow->lectureSets = $lectureSets;

        return $scheduleRow;
    }
}
