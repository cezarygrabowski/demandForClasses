<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\AssignDemand;

class AssignDemandHandler
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(AssignDemand $command): void
    {
        $this->userSerivce->
        $command->getDemand()->assign($command->getAssignor(), $command->getAssignee(), $command->getLectureSetTypes());
    }
}