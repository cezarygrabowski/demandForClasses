<?php

namespace App\DTO;

use App\Domain\ScheduleReader;

class ScheduleRow
{
    public $group;
    public $subject;
    public $lectureType;
    public $hours;
    public $yearNumber;
    public $groupType;
    public $semester;
    public $department;
    public $institute;
    public $shortenedSubject;

    /**
     * @return ScheduleRow[]
     */
    public static function fromCsvContent(array $data): array
    {
        $scheduleRows = [];
        foreach ($data as $row) {
            $scheduleRow = new self();
            $scheduleRow->group = $row[ScheduleReader::GROUP];
            $scheduleRow->shortenedSubject = $row[ScheduleReader::SHORTENED_SUBJECT];
            $scheduleRow->subject = $row[ScheduleReader::SUBJECT];
            $scheduleRow->lectureType = $row[ScheduleReader::LECTURE_TYPE];
            $scheduleRow->hours = $row[ScheduleReader::HOURS];
            $scheduleRow->yearNumber = $row[ScheduleReader::YEAR_NUMBER];
            $scheduleRow->groupType = $row[ScheduleReader::GROUP_TYPE];
            $scheduleRow->semester = $row[ScheduleReader::SEMESTER];
            $scheduleRow->department = $row[ScheduleReader::DEPARTMENT];
            $scheduleRow->institute = $row[ScheduleReader::INSTITUTE];

            $scheduleRows[] = $scheduleRow;
        }

        return $scheduleRows;
    }
}