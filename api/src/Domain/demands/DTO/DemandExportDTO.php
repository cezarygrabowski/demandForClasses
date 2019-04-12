<?php


namespace App\Domain\demands\DTO;


class DemandExportDTO
{
    const LECTURE_TYPE = 0;
    const DEMAND_UUID = 1;
    const LECTURER = 2;
    const SUBJECT_NAME = 3;
    const SCHOOL_YEAR = 4;
    const GROUP = 5;
    const GROUP_TYPE = 6;
    const SEMESTER = 7;
    const INSTITUTE = 8;
    const DEPARTMENT = 9;
    const HOURS = 10;
    const WEEK_1 = 11;
    const WEEK_2 = 12;
    const WEEK_3 = 13;
    const WEEK_4 = 14;
    const WEEK_5 = 15;
    const WEEK_6 = 16;
    const WEEK_7 = 17;
    const WEEK_8 = 18;
    const WEEK_9 = 19;
    const WEEK_10 = 20;
    const WEEK_11 = 21;
    const WEEK_12 = 22;
    const WEEK_13 = 23;
    const WEEK_14 = 24;
    const WEEK_15 = 25;

    private $lectureType;
    private $demandUuid;
    private $lecturer;
    private $subjectName;
    private $schoolYear;
    private $group;
    private $groupType;
    private $semester;
    private $institute;
    private $department;
    private $hours;
    private $allocatedWeeks = [
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0,
        6 => 0,
        7 => 0,
        8 => 0,
        9 => 0,
        10 => 0,
        11 => 0,
        12 => 0,
        13 => 0,
        14 => 0,
        15 => 0
    ];
}
