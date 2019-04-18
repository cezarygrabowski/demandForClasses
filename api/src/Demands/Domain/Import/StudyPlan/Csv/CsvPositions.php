<?php


namespace Demands\Domain\Import\StudyPlan\Csv;


use Demands\Domain\Import\StudyPlan\Positions;

class CsvPositions implements Positions
{
    const GROUP = 0;
    const GROUP_TYPE = 1;
    const SUBJECT_NAME = 2;
    const SUBJECT_SHORT_NAME = 3;
    const LECTURE_TYPES = 4;
    const YEAR_NUMBER = 5;
    const LECTURE_HOURS = 6;
    const SEMESTER = 7;
    const DEPARTMENT = 8;
    const INSTITUTE = 9;
}
