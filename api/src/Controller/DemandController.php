<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Repository\DemandRepository;
use App\Repository\LectureRepository;
use App\Service\DemandService;
use App\Service\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DemandController
 * @package App\Controller
 * @Route("/demands")
 */
class DemandController extends AbstractController
{
    /**
     * @var DemandService
     */
    private $demandService;

    /**
     * @var HttpService
     */
    private $httpService;

    /**
     * @var LectureRepository
     */
    private $lectureRepository;

    /**
     * @var DemandRepository
     */
    private $demandRepository;

    public function __construct(
        DemandService $demandService,
        HttpService $httpService,
        DemandRepository $demandRepository,
        LectureRepository $lectureRepository
    ) {
        $this->demandService = $demandService;
        $this->httpService = $httpService;
        $this->demandRepository = $demandRepository;
        $this->lectureRepository = $lectureRepository;
    }

    /**
     * @Route("/", name="list_demands", methods={"GET"})
     */
    public function list(){
        $demands = $this->demandService->findAll($this->httpService->getCurrentUser());

        return $this->httpService->createCollectionResponse($demands);
    }

    /**
     * @Route("/buildings", name="list_buildings", methods={"GET"})
     */
    public function listBuildings(){
        $buildings = $this->demandService->findAllBuildings();

        return $this->httpService->createCollectionResponse($buildings);
    }

    /**
     * @Route("/update/{id}", name="update_demand", methods={"POST"})
     */
    public function update(Request $request, Demand $demand){
        $data = json_decode($request->getContent(), true);
        $this->demandService->updateDemand($demand, $this->httpService->getCurrentUser(), $data);

        return $this->httpService->createSuccessResponse();
    }

    /**
     * @Route("/cancel/{id}", name="cancel_demand", methods={"POST"})
     */
    public function cancel(Demand $demand) {
        $this->demandService->cancelDemand($demand, $this->httpService->getCurrentUser());

        return $this->httpService->createSuccessResponse();
    }

    /**
     * @Route("/details/{id}", name="get_details", methods={"GET"})
     */
    public function getDetails(Demand $demand){
        return $this->httpService->createItemResponse($demand);
    }

    /**
     * @Route("/export", name="export_demands", methods={"POST"})
     */
    public function exportDemands(){
        $results = $this->lectureRepository->findAll();
        $response = $this->httpService->prepareStreamedResponse($results);

        return $this->httpService->downloadCsvFileResponse($response, 'export.csv');
    }
}
