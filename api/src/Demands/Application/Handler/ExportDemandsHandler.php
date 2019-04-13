<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\ExportDemands;
use Demands\Domain\Export\ExportDemandDto;
use Demands\Domain\FileMaker;
use Demands\Domain\Repository\DemandRepository;
use Users\Domain\User;

class ExportDemandsHandler
{
    /**
     * @var FileMaker
     */
    private $fileMaker;

    /**
     * @var DemandRepository
     */
    private $demandRepository;

    public function __construct(
        FileMaker $fileMaker,
        DemandRepository $demandRepository
    ) {
        $this->fileMaker = $fileMaker;
        $this->demandRepository = $demandRepository;
    }

    /**
     * Return string containing headers and content that is ready to be inserted into Csv file
     * @param ExportDemands $command
     * @return string
     */
    public function handle(ExportDemands $command): string
    {
        $demands = $this->demandRepository->findByIdentifiers($command->getUuids());
        $this->markDemandsAsExported($demands, $command->getUser());

        $demandsForExport = ExportDemandDto::prepareDemands($demands);
        return $this->fileMaker->prepareFileContent($demandsForExport);
    }

    private function markDemandsAsExported(array $demands, User $user)
    {
        foreach ($demands as $demand) {
            $demand->markAsExported($user);
        }
    }
}