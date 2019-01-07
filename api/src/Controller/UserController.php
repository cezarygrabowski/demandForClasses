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
    private $userService;
    /**
     * @var HttpService
     */
    private $httpService;

    public function __construct(
        UserService $userService,
        HttpService $httpService
    ) {
        $this->userService = $userService;
        $this->httpService = $httpService;
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @Route("/register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $username = 'johndoe';
        $password = 'test';
        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush();
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * @return Response
     * @Route("/api", methods={"POST"})
     */
    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

    /**
     * @Route("/lecturers/{id}")
     */
    public function getQualifiedLecturers(Demand $demand) {
        $users = $this->userService->getQualifiedLecturers($demand);

        return $this->httpService->createCollectionResponse($users);
    }
}