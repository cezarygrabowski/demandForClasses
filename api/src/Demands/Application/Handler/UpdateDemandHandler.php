<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\UpdateDemand;

class UpdateDemandHandler
{
    public function handle(UpdateDemand $command)
    {
        $command->getDemand()->update($command->getDetailsToUpdate());
    }
}