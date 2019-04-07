<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Entity\User;
use App\Service\HttpService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var HttpService
     */
    private $httpService;

    public function __construct(
        UserService $userService,
        HttpService $httpService
    )
    {
        $this->userService = $userService;
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

        $data = $this->httpService->readCsvContent($file, true);
        $this->userService->generateUsers($data); //TODO HERE

        return $this->httpService->createSuccessResponse();
    }
}
