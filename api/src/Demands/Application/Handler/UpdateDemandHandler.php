<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\UpdateDemand;
use Demands\Application\Service\DemandService;

class UpdateDemandHandler
{
    /**
     * @var DemandService
     */
    private $demandService;

    public function __construct(DemandService $demandService)
    {
        $this->demandService = $demandService;
    }

    public function handle(UpdateDemand $command)
    {
        $this->demandService->update($command->getDemand(), $command->getDetailsToUpdate());
    }
}
