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
     * @param int $status
     * @return Demand[]
     */
    public function listAllWithStatus(int $status): array;
}