<?php


namespace Users\Infrastructure\Symfony\Controller;


use Common\Http\HttpService;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

    public function updateProfile(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->userService->updateAutomaticalSendToPlanners($this->getUser(), $data);

        return $this->httpService->createSuccessResponse();
    }

    public function getQualifiedLecturers(int $demandId)
    {
        $users = $this->userService->getQualifiedLecturers($demand);

        return $this->httpService->createCollectionResponse($users);
    }

    public function roles()
    {
        return $this->httpService->createCollectionResponse($this->httpService->getCurrentUser()->getRoles());
    }

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