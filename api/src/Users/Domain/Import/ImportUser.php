<?php


namespace Users\Domain\Import;


use Users\Domain\Role;

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
        $importUser = new ImportUser();

        $importUser->userName = $row[CsvPositions::USER_NAME];
        $importUser->roleName = Role::ROLES_STRING_TO_INT[$row[CsvPositions::ROLE]];
        $qualifications = explode(',', $row[CsvPositions::QUALIFICATIONS]);
        $importUser->qualifications = [];
        foreach ($qualifications as $qualification) {
            $subject = explode(' - ', $qualification);
            $importUser->qualifications[] = new Qualification($subject[1], $subject[0]);
        }
        $importUser->workingHours = explode(',', $row[CsvPositions::WORKING_HOURS]);
        $importUser->semester = $row[CsvPositions::SEMESTER];

        return $importUser;
    }
}