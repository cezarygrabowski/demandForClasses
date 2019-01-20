<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Repository\DemandRepository;
use App\Service\DemandService;
use App\Service\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $demands = $this->demandService->findAll();

        return $this->httpService->createCollectionResponse($demands);
    }
    /**
     * @Route("/export", name="export_demands", methods={"GET"})
     */
    public function export(){
        // get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {

            $em = $container->get('doctrine')->getManager();

            $results = $this->demandRepository->findAll();
            $handle = fopen('php://output', 'r+');

            foreach ($results as $result) {
                fputcsv($handle, $result->toArray());
//                fputcsv($handle, ['test']);
            }
            fclose($handle);
        });

        header('Content-Type: application/octet-stream');
        header('Content-Disposition','attachment; filename="export.csv"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
//        header('Content-Length: ' . filesize($handle));
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');

        return $response;
//        $this->demandService->exportDemands();
//        return $this->httpService->createCollectionResponse($demands);
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
        $this->getDoctrine()->getEntityManager()->flush();

        return $this->httpService->createSuccessResponse();
    }

    /**
     * @Route("/details/{id}", name="get_details", methods={"GET"})
     */
    public function getDetails(Demand $demand){
        return $this->httpService->createItemResponse($demand);
    }
}
