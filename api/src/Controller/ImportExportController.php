<?php

namespace App\Controller;

use App\Service\DemandService;
use App\Service\ImportExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImportExportController
 * @package App\Controller
 */
class ImportExportController extends AbstractController
{
    private $importExportService;

    public function __construct(
        ImportExportService $importExportService
    ) {
        $this->importExportService = $importExportService;
    }

    /**
     * @Route("/", name="ex")
     */
    public function exportDemands(): Response
    {
//        $this->importExportService->exportDemands($data);
    }

    /**
     * @Route("/import-teachers", name="import_teachers")
     */
    public function importTeachers(Request $request): Response
    {
//        $this->importExportService->importTeachers($data);
    }

    /**
     * @Route("/import-schedules", name="import_schedules")
     */
    public function importSchedules(Request $request): Response
    {

        $file = $request->files->get('file');
        $file = fopen($file, 'r');
        $data = [];
        while (($line = fgetcsv($file)) !== FALSE) {
            //$line is an array of the csv elements
            $data[] = $line;
        }
        fclose($file);

        /** remove first element which is headers */
        array_shift($data);

        $this->importExportService->importSchedules($data);
    }


}
