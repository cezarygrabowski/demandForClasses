<?php


namespace Demands\Domain\Assign;


use Demands\Domain\Demand;
use Users\Domain\User;

class AssignDemand
{
    /**
     * @var Demand
     */
    public $demand;

    /**
     * User who assignes a demand
     * @var User
     */
    public $assignor;

    /**
     * User who gets a demand
     * @var User
     */
    public $assignee;

    /**
     * @var LectureSet[]
     */
    public $lectureSets;

    public static function create($data): self
    {
        $assignDemand = new self();

        //TODO implement me

        return $assignDemand;
    }
}