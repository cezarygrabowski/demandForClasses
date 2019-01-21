<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Repository\DemandRepository;
use App\Service\DemandService;
use App\Service\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
    private $demandRepository;

    /**
     * DemandController constructor.
     * @param DemandService $demandService
     * @param HttpService $httpService
     */
    public function __construct(
        DemandService $demandService,
        HttpService $httpService,
        DemandRepository $demandRepository
    ) {
        $this->demandService = $demandService;
        $this->httpService = $httpService;
        $this->demandRepository = $demandRepository;
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
        $this->getDoctrine()->getEntityManager()->flush();

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
}
