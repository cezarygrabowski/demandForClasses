<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\DeclineDemand;

class DeclineDemandHandler
{
    public function handle(DeclineDemand $command): void
    {
        $command->getDemand()->decline($command->getUser());
    }
}