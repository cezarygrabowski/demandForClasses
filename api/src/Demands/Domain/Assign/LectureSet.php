<?php


namespace Demands\Domain\Assign;


use Users\Domain\User;

class LectureSet
{
    /**
     * @var int
     */
    public $type;

    /**
     * @var User
     */
    public $assignor;
}