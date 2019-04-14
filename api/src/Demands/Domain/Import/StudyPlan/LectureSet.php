<?php


namespace Demands\Domain\Import\StudyPlan;


class LectureSet
{
    public $type;
    public $hours;

    public function __construct(int $type, int $hours)
    {
        $this->type = $type;
        $this->hours = $hours;
    }
}