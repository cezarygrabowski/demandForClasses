<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\ImportStudyPlans;
use Demands\Application\Service\DemandService;
use Demands\Domain\Demand;

class ImportStudyPlansHandler
{

    /**
     * @var DemandService
     */
    private $demandService;

    public function __construct(DemandService $demandService)
    {
        $this->demandService = $demandService;
    }

    /**
     * @param ImportStudyPlans $command
     * @return Demand[]
     */
    public function handle(ImportStudyPlans $command): array
    {
        return $this->demandService->createDemandsFromStudyPlans($command->getUser(), $command->getStudyPlans());
    }
}