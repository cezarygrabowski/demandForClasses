<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Service\DemandService;
use App\Service\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DemandController
 * @package App\Controller
 * @Route("/demands")
 */
class DemandController extends AbstractController
{
    private $demandService;

    /**
     * @var HttpService
     */
    private $httpService;

    /**
     * DemandController constructor.
     * @param DemandService $demandService
     * @param HttpService $httpService
     */
    public function __construct(
        DemandService $demandService,
        HttpService $httpService
    ) {
        $this->demandService = $demandService;
        $this->httpService = $httpService;
    }

    /**
     * @Route("/", name="list_demands", methods={"GET"})
     */
    public function list(){
        $demands = $this->demandService->findAll();

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
        $this->demandService->updateDemand($demand, $this->getUser(), $data);

        return new JsonResponse("success");
    }

    /**
     * @Route("/details/{id}", name="get_details", methods={"GET"})
     */
    public function getDetails(Demand $demand){
        return $this->httpService->createItemResponse($demand);
    }
}