<?php


namespace Demands\Application\Handler;


use Demands\Domain\Printing\PrintDemand;

class DownloadDemandHandler
{
    public function handle(PrintDemand $command)
    {
        $printDemand = PrintDemand::create($command->getDemand());
        //force download pdf
    }
}