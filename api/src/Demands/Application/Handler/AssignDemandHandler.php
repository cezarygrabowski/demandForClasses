<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\AssignDemand;

class AssignDemandHandler
{
    public function handle(AssignDemand $command): void
    {
        $command->getDemand()->assign($command->getAssignor(), $command->getAssignee(), $command->getLectureSetTypes());
    }
}