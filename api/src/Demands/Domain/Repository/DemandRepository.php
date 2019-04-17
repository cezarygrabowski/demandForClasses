<?php

namespace Demands\Domain\Repository;

use Demands\Domain\Demand;
use Users\Domain\User;

interface DemandRepository
{
    /**
     * @param User $user
     * @return Demand[]
     */
    public function listAllForUser(User $user): array;

    /**
     * @param array $uuids
     * @return Demand[]
     */
    public function findByIdentifiers(array $uuids): array;

    /**
     * @param array $statuses
     * @return Demand[]
     */
    public function listAllWithStatuses(array $statuses): array;

    public function findOneByUuid(string $demandUuid): ?Demand;
}
