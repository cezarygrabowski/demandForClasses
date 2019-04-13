<?php


namespace Demands\Application\Service;


use Demands\Domain\Repository\DemandRepository;
use Demands\Domain\StatusResolver;
use Users\Domain\User;

class DemandService
{
    /**
     * @var DemandRepository
     */
    private $demandRepository;

    /**
     * @var StatusResolver
     */
    private $statusResolver;

    public function __construct(
        DemandRepository $demandRepository,
        StatusResolver $statusResolver
    ) {
        $this->demandRepository = $demandRepository;
        $this->statusResolver = $statusResolver;
    }

    public function listAllForUser(User $user)
    {
        if($user->isTeacher()) {
            return $this->demandRepository->listAllForUser($user);
        } else {
            return $this->demandRepository->listAllWithStatus($this->statusResolver->resolveStatusForDemandListing($user));
        }
    }
}