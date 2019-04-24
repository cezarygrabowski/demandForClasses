<?php

namespace Demands\Infrastructure\Symfony\Controller;

use Demands\Application\Command\AcceptDemand;
use Demands\Domain\Query\Details\DemandDetails;
use Common\Http\HttpService;
use Demands\Application\Command\AssignDemand;
use Demands\Application\Command\DeclineDemand;
use Demands\Application\Command\DownloadDemand;
use Demands\Application\Command\ExportDemands;
use Demands\Application\Command\ImportStudyPlans;
use Demands\Application\Command\UpdateDemand;
use Demands\Application\Service\DemandService;
use Demands\Domain\Demand;
use Demands\Domain\Import\StudyPlan\StudyPlansExtractor;
use Demands\Domain\Repository\PlaceRepository;
use Demands\Domain\Update\DetailsToUpdate;
use Dompdf\Dompdf;
use Dompdf\Options;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

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
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(
        CommandBus $commandBus,
        HttpService $httpService,
        DemandService $demandService,
        TokenStorageInterface $tokenStorage,
        PlaceRepository $placeRepository,
        Environment $twig
    ) {
        $this->commandBus = $commandBus;
        $this->httpService = $httpService;
        $this->demandService = $demandService;
        $this->tokenStorage = $tokenStorage;
        $this->placeRepository = $placeRepository;
        $this->twig = $twig;
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

    public function exportDemands()
    {
        $uuids = []; // TODO GET UUIDS
        $command = new ExportDemands($uuids, $this->httpService->getCurrentUser());

        $content = $this->commandBus->handle($command);

        return new Response($content);
    }

    public function downloadDemand(Demand $demand)
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->twig->render('@demands/demand_pdf.html.twig', [
            'demand' => $demand
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

        $response = new Response();
        $response->setCharset('UTF-8');

        return $response;
    }

    public function assignDemand(Request $request, Demand $demand)
    {
        $data = json_decode($request->getContent(), true);
        $command = new AssignDemand(\Demands\Domain\Assign\AssignDemand::create($data));

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
