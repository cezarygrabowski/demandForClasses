<?php


namespace App\Demands\Domain\Query\Details;


use Demands\Domain\Query\Details\Lecturer;

class LectureSet
{
    public $uuid;
    public $type;
    public $hoursToDistribute;
    /**
     * @var Lecturer
     */
    public $lecturer;
    public $notes;
    public $allocatedWeeks = [];
}
