<?php


namespace Users\Domain\Import;


use Users\Domain\Role;
use Users\Domain\User;

class ImportUser
{
    /**
     * @var Qualification
     */
    public $qualifications;
    public $userName;
    public $roleName;
    public $workingHours;
    public $semester;

    public static function create($row)
    {
        $importUser = new self();

        $importUser->userName = $row[CsvPositions::USER_NAME];
        $importUser->roleName = User::ROLES_VALUE_TO_KEY[$row[CsvPositions::ROLE]];
        $qualifications = explode(',', $row[CsvPositions::QUALIFICATIONS]);
        $importUser->qualifications = [];
        foreach ($qualifications as $qualification) {
            $subject = explode(' - ', $qualification);
            $importUser->qualifications[] = new Qualification($subject[1], $subject[0]);
        }
        $importUser->workingHours = explode(';', $row[CsvPositions::WORKING_HOURS]);
        $importUser->semester = $row[CsvPositions::SEMESTER];

        return $importUser;
    }
}
