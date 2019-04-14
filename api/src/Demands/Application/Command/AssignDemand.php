<?php


namespace Demands\Application\Command;


use Demands\Domain\Demand;
use Users\Domain\User;

class AssignDemand
{
    /**
     * @var Demand
     */
    private $demand;

    /**
     * User who assignes a demand
     * @var User
     */
    private $assignor;

    /**
     * User who gets a demand
     * @var User
     */
    private $assignee;

    /**
     * @var array
     */
    private $lectureSetTypes;

    public function __construct(
        Demand $demand,
        array $lectureSetTypes,
        User $assignor,
        User $assignee
    ) {
        $this->demand = $demand;
        $this->lectureSetTypes = $lectureSetTypes;
        $this->assignor = $assignor;
        $this->assignee = $assignee;
    }

    public function getDemand(): Demand
    {
        return $this->demand;
    }

    public function getAssignor(): User
    {
        return $this->assignor;
    }

    public function getAssignee(): User
    {
        return $this->assignee;
    }

    public function getLectureSetTypes(): array
    {
        return $this->lectureSetTypes;
    }
}