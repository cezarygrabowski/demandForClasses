<?php

namespace App\Controller;

use App\DTO\ScheduleRow;
use App\Service\HttpService;
use App\Service\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DemandController
 * @package App\Controller
 * @Route("/schedules")
 */
class ScheduleController extends AbstractController
{
    /**
     * @var HttpService
     */
    private $httpService;
    /**
     * @var ScheduleService
     */
    private $scheduleService;

    public function __construct(
        HttpService $httpService,
        ScheduleService $scheduleService
    ) {
        $this->httpService = $httpService;
        $this->scheduleService = $scheduleService;
    }

    /**
     * @Route("/import", name="import_schedules")
     */
    public function importSchedules(Request $request): Response
    {
        $file = $request->files->get('file');

        $data = $this->httpService->readCsvContent($file, true);
        $this->scheduleService->importSchedules(ScheduleRow::fromCsvContent($data));

        return $this->httpService->createSuccessResponse();
    }
}