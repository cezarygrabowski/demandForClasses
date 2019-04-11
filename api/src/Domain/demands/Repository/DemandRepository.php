<?php

namespace App\Domain\demands\Repository;

use App\Domain\demands\Demand;
use App\Domain\users\User;

interface DemandRepository
{
    /**
     * @return Demand[]
     */
    public function listAllForUser(User $user): array;
}