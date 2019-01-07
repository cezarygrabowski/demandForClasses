<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ImportExportService
{
    private $demandService;
    private $entityManager;

    public function __construct(
        DemandService $demandService,
        EntityManagerInterface $entityManager
    ) {
        $this->demandService = $demandService;
        $this->entityManager = $entityManager;
    }

    public function importTeachers(array $teachers): bool
    {
        // TODO: Implement importTeachers() method.
    }

    public function importSchedules(array $schedules): void
    {
        $this->demandService->generateDemands($schedules);
    }

    public function exportDemands($data)
    {

    }
}