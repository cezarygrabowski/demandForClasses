<?php


namespace Users\Infrastructure\Symfony\Controller;


use Common\Http\HttpService;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Users\Application\Command\ImportUsers;
use Users\Domain\Import\CsvExtractor;

class UserController
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
     * @return Response
     * @Route("/", methods={"GET"})
     */
    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

    /**
     * @Route("/lecturers/automatically-send-to-planners", name="automatically_send_to_planners", methods={"POST"})
     */
    public function automaticallySendToPlanners(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->userService->updateAutomaticalSendToPlanners($this->getUser(), $data);

        return $this->httpService->createSuccessResponse();
    }

    /**
     * @Route("/lecturers/{id}")
     */
    public function getQualifiedLecturers(Demand $demand)
    {
        $users = $this->userService->getQualifiedLecturers($demand);

        return $this->httpService->createCollectionResponse($users);
    }

    /**
     * @Route("/lecturer-roles")
     */
    public function roles()
    {
        return $this->httpService->createCollectionResponse($this->httpService->getCurrentUser()->getRoles());
    }

    /**
     * @Route("/import-teachers", name="import_lecturers")
     */
    public function importLecturers(Request $request): Response
    {
        $file = $request->files->get('file');
        $command = new ImportUsers(
            $this->httpService->getCurrentUser(),
            CsvExtractor::extract($file)
        );

        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }
}