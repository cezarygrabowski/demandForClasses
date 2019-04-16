<?php


namespace Demands\Domain\Assign;


class AssignDemand
{
    /**
     * @var string
     */
    public $demandUuid;

    /**
     * User who assignes a demand
     * @var string
     */
    public $assignorUuid;

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