<?php

namespace App\Controller;

use App\Entity\Demand;
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
     * @Route("", name="list_demands", methods={"GET"})
     */
    public function list(){
        $demands = $this->demandService->findAll();

        return $this->httpService->createCollectionResponse($demands);
    }

    /**
     * @Route("/update/{id}", name="update_demand", methods={"POST"})
     */
    public function update(Request $request, Demand $demand){
        $this->demandService->updateDemand($demand, $this->getUser(), $data);
    }

    /**
     * @Route("/details/{id}", name="update_demand", methods={"GET"})
     */
    public function getDetails(Demand $demand){
        return $this->httpService->createItemResponse($demand);
    }
}