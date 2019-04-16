<?php


namespace Demands\Application\Handler;


use Demands\Application\Command\AssignDemand;
use Demands\Domain\Repository\DemandRepository;
use Users\Domain\Repository\UserRepository;
use Zend\EventManager\Exception\DomainException;

class AssignDemandHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var DemandRepository
     */
    private $demandRepository;

    /**
     * AssignDemandHandler constructor.
     * @param UserRepository $userRepository
     * @param DemandRepository $demandRepository
     */
    public function __construct(UserRepository $userRepository, DemandRepository $demandRepository)
    {
        $this->userRepository = $userRepository;
        $this->demandRepository = $demandRepository;
    }

    public function handle(AssignDemand $command): void
    {
        $demand = $this->demandRepository->findOneByUuid($command->getAssignDemand()->demandUuid);
        if (!$demand) {
            throw new DomainException("Nie znaleziono zapotrzebowania o identyfikatorze: " . $command->getAssignDemand()->demandUuid . " nie istnieje");
        }

        $assignor = $this->userRepository->findByUuid($command->getAssignDemand()->assignorUuid);
        if (!$assignor) {
            throw new DomainException("Użytkownik o identyfikatorze: " . $command->getAssignDemand()->assignorUuid . " nie istnieje");
        }

        foreach ($command->getAssignDemand()->lectureSets as $lectureSet) {
            $assignee = $this->userRepository->findByUuid($lectureSet->assigneeUuid);
            if (!$assignee) {
                throw new DomainException("Użytkownik o identyfikatorze: " . $command->getAssignDemand()->assignorUuid . " nie istnieje");
            }

            try {
                $demand->assign($assignor, $lectureSet->type, $assignee);
            } catch (\Exception $e) {
                throw new DomainException($e->getMessage());
            }
        }
    }
}