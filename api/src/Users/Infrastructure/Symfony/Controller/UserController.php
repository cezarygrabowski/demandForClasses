<?php


namespace Users\Infrastructure\Symfony\Controller;


use Common\Http\HttpService;
use Demands\Domain\Demand;
use Demands\Domain\Subject;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Users\Application\Command\ImportUsers;
use Users\Application\Command\UpdateProfile;
use Users\Application\Service\UserService;
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

    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        CommandBus $commandBus,
        HttpService $httpService,
        UserService $userService,
        TokenStorageInterface $tokenStorage
    ) {
        $this->commandBus = $commandBus;
        $this->httpService = $httpService;
        $this->userService = $userService;
        $this->tokenStorage = $tokenStorage;
    }

    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

    public function updateProfile(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $command = new UpdateProfile($this->tokenStorage->getToken()->getUser(), $data);
        $this->commandBus->handle($command);

        return $this->httpService->createSuccessResponse();
    }

    public function getProfile()
    {
        $details = $this->userService->getUserDetails($this->tokenStorage->getToken()->getUser());
        return $this->httpService->createItemResponse($details);
    }

    public function getQualifiedLecturers(string $subjectName)
    {
        $users = $this->userService->getQualifiedLecturers($subjectName);

        return $this->httpService->createCollectionResponse($users);
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

    public function lecturers()
    {
        $users = $this->userService->getAllLecturers();

        return $this->httpService->createCollectionResponse($users);
    }
}
