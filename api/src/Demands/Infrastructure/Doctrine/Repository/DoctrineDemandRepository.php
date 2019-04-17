<?php


namespace Demands\Infrastructure\Doctrine\Repository;


use Demands\Domain\Demand;
use Demands\Domain\Repository\DemandRepository;
use Users\Domain\User;

class DoctrineDemandRepository implements DemandRepository
{
    /**
     * @param User $user
     * @return Demand[]
     */
    public function listAllForUser(User $user): array
    {
        // TODO: Implement listAllForUser() method.
    }
    /**
     * @param array $uuids
     * @return Demand[]
     */
    public function findByIdentifiers(array $uuids): array
    {
        // TODO: Implement findByIdentifiers() method.
    }
    /**
     * @param array $status
     * @return Demand[]
     */
    public function listAllWithStatuses(array $status): array
    {
        // TODO: Implement listAllWithStatuses() method.
    }
    public function findOneByUuid(string $demandUuid): ?Demand
    {
        // TODO: Implement findOneByUuid() method.
    }
}
