<?php


namespace Demands\Application\Command;

class AssignDemand
{
    /**
     * @var \Demands\Domain\Assign\AssignDemand
     */
    private $assignDemand;

    public function __construct(\Demands\Domain\Assign\AssignDemand $assignDemand) {
        $this->assignDemand = $assignDemand;
    }

    /**
     * @return \Demands\Domain\Assign\AssignDemand
     */
    public function getAssignDemand(): \Demands\Domain\Assign\AssignDemand
    {
        return $this->assignDemand;
    }
}