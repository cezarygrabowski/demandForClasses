<?php

namespace Demands\Infrastructure\Symfony\Controller;

use Demands\Application\Command\AcceptDemand;
use Demands\Application\Command\DownloadDemand;
use Demands\Domain\Query\Details\DemandDetails;
use Common\Http\HttpService;
use Demands\Application\Command\AssignDemand;
use Demands\Application\Command\DeclineDemand;
use Demands\Application\Command\ExportDemands;
use Demands\Application\Command\ImportStudyPlans;
use Demands\Application\Command\UpdateDemand;
use Demands\Application\Service\DemandService;
use Demands\Domain\Demand;
use Demands\Domain\Import\StudyPlan\StudyPlansExtractor;
use Demands\Domain\Repository\PlaceRepository;
use Demands\Domain\Update\DetailsToUpdate;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Zend\EventManager\Exception\DomainException;
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

    /**
     * @var DemandService
     */
    private $demandService;

    /**
     * @var \Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var PlaceRepository
     */
    private $placeRepository;
    private $serializer;

    public function __construct(
        CommandBus $commandBus,
        HttpService $httpService,
        DemandService $demandService,
        TokenStorageInterface $tokenStorage,
        PlaceRepository $placeRepository,
        SerializerInterface $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->httpService = $httpService;
        $this->demandService = $demandService;
        $this->tokenStorage = $tokenStorage;
        $this->placeRepository = $placeRepository;
        $this->serializer = $serializer;
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

    public function list()
    {
        $demands = $this->demandService->listAllForUser($this->tokenStorage->getToken()->getUser());

        return new JsonResponse($demands);
    }

    public function listPlaces()
    {
        return $this->httpService->createCollectionResponse(
            $this->placeRepository->findAll()
        );
    }

    public function updateDemand(Request $request, Demand $demand)
    {
        $data = json_decode($request->getContent(), true);
        $command = new UpdateDemand(
            DetailsToUpdate::fromData($data),
            $demand
        );

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }

    public function declineDemand(Demand $demand)
    {
        $command = new DeclineDemand(
            $demand,
            $this->httpService->getCurrentUser()
        );

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }

    public function getDetails(Demand $demand)
    {
        return $this->httpService->createItemResponse(DemandDetails::fromDemand($demand));
    }

    public function exportDemands(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $command = new ExportDemands($data['uuids'], $this->httpService->getCurrentUser());

        $content = $this->commandBus->handle($command);

        $response = $this->httpService->downloadCsvFileResponse(
            new StreamedResponse(
                function() use ($content) {
                    $handle = fopen('php://output', 'w+');
                    fputcsv($handle, explode(',', $content[0]));
                    foreach ($content[1] as $item) {
                        fputcsv($handle, $item);
                    }
                    fclose($handle);
                }
        ), 'test.csv');

//        $disposition = $response->headers->makeDisposition(
//            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
//            'demands.csv'
//        );
//
//        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
//        $response->headers->set('Content-Disposition', $disposition);
        return $response;

    }

    public function downloadDemand(Demand $demand)
    {
        $command = new DownloadDemand($demand);

        $pdf = $this->commandBus->handle($command);

        return new Response($pdf->toString());
    }

    public function assignDemand(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $command = new AssignDemand(\Demands\Domain\Assign\AssignDemand::create($data, $this->tokenStorage->getToken()->getUser()->getUuid()));

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }

    public function acceptDemand(Request $request, Demand $demand)
    {
        $data = json_decode($request->getContent(), true);
        $command = new AcceptDemand($demand, $this->tokenStorage->getToken()->getUser());

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }
}
