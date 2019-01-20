<?php

namespace App\Controller;

use App\Repository\LectureRepository;
use App\Service\DemandService;
use App\Service\HttpService;
use App\Service\ImportExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImportExportController
 * @package App\Controller
 */
class ImportExportController extends AbstractController
{
    private $importExportService;
    private $httpService;
    private $lectureRepository;

    public function __construct(
        ImportExportService $importExportService,
        HttpService $httpService,
        LectureRepository $lectureRepository
    ) {
        $this->importExportService = $importExportService;
        $this->httpService = $httpService;
        $this->lectureRepository = $lectureRepository;
    }

    /**
     * @Route("/demands/export", name="export_demands", methods={"POST"})
     */
    public function export(){

        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {

            $results = $this->lectureRepository->findAll();
            $handle = fopen('php://output', 'r+');

            foreach ($results as $result) {
                fputcsv($handle, $result->toArray());
//                fputcsv($handle, ['test1', 'test2']);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response->sendHeaders()->sendContent();
    }

    /**
     * @Route("/import-teachers", name="import_lecturers")
     */
    public function importLecturers(Request $request): Response
    {
        $file = $request->files->get('file');
        $file = fopen($file, 'r');
        $data = [];
        while (($line = fgetcsv($file)) !== FALSE) {
            $data[] = $line;
        }
        fclose($file);

        /** remove first element which is header */
        array_shift($data);

        $this->importExportService->importUsers($data);

        return $this->httpService->createSuccessResponse();
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
            $data[] = $line;
        }
        fclose($file);

        /** remove first element which is header */
        array_shift($data);

        $this->importExportService->importSchedules($data);

        return $this->httpService->createSuccessResponse();
    }


}
