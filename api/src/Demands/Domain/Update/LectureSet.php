<?php


namespace Demands\Domain\Update;


class LectureSet
{
    /**
     * @var int
     */
    public $type;

    /**
     * @var AllocatedWeek[]
     */
    public $allocatedWeeks = [];

    /**
     * @var string
     */
    public $notes;
}
