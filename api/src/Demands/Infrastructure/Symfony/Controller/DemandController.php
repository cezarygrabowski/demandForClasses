<?php

namespace Demands\Infrastructure\Symfony\Controller;

use Common\Http\HttpService;
use Demands\Application\Command\AssignDemand;
use Demands\Application\Command\DeclineDemand;
use Demands\Application\Command\DownloadDemand;
use Demands\Application\Command\ExportDemands;
use Demands\Application\Command\ImportStudyPlans;
use Demands\Application\Command\UpdateDemand;
use Demands\Domain\Demand;
use Demands\Domain\Import\StudyPlan\StudyPlansExtractor;
use Demands\Domain\Update\DetailsToUpdate;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DemandController
 * @package App\Controller
 * @Route("/demands")
 */
class DemandController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var HttpService
     */
    private $httpService;

    public function __construct(
        CommandBus $commandBus,
        HttpService $httpService
    ) {
        $this->commandBus = $commandBus;
        $this->httpService = $httpService;
    }

    /**
     * @Route("/import-study-plans", name="import_schedules")
     */
    public function importStudyPlans(Request $request): Response
    {
        $command = new ImportStudyPlans(
            $this->httpService->getCurrentUser(),
            StudyPlansExtractor::extract($request->files->get('file'))
        );

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }

    /**
     * @Route("/", name="list_demands", methods={"GET"})
     */
    public function list()
    {
        //TODO
//        return $this->httpService->createCollectionResponse($demands);
    }

    /**
     * @Route("/buildings", name="list_buildings", methods={"GET"})
     */
    public function listBuildings()
    {
        //TODO
//        return $this->httpService->createCollectionResponse($buildings);
    }

    /**
     * @Route("/update/{uuid}", name="update_demand", methods={"POST"})
     */
    public function update(Request $request, Demand $demand)
    {
        $data = json_decode($request->getContent(), true);
        $command = new UpdateDemand(
            DetailsToUpdate::fromData($data),
            $demand
        );

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }

    /**
     * @Route("/decline/{uuid}", name="decline_demand", methods={"POST"})
     */
    public function decline(Demand $demand)
    {
        $command = new DeclineDemand(
            $demand,
            $this->httpService->getCurrentUser()
        );

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }

    /**
     * @Route("/details/{uuid}", name="get_details", methods={"GET"})
     */
    public function getDetails(Demand $demand)
    {
        //TODO
        return $this->httpService->createItemResponse($demand);
    }

    /**
     * @Route("/export", name="export_demands", methods={"POST"})
     */
    public function exportDemands()
    {
        $uuids = []; // TODO GET UUIDS
        $command = new ExportDemands($uuids, $this->httpService->getCurrentUser());

        $content = $this->commandBus->handle($command);

        return new Response($content);
    }

    /**
     * @Route("/download-demand/{uuid}", name="download_demand", methods={"POST"})
     */
    public function downloadDemand(Demand $demand)
    {
        $uuids = []; // TODO GET UUIDS
        $command = new DownloadDemand($demand);

        $content = $this->commandBus->handle($command);

        return new Response($content);
    }

    /**
     * @Route("/assign-demand/{uuid}", name="download_demand", methods={"POST"})
     */
    public function assignDemand(Request $request, Demand $demand)
    {
        $data = json_decode($request->getContent(), true);
        $command = new AssignDemand(\Demands\Domain\Assign\AssignDemand::create($data));

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }
}
