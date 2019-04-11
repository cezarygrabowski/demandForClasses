<?php

namespace App\Infrastructure\demands\InMemory\Repository;

use App\Domain\demands\Demand;
use App\Domain\demands\Repository\DemandRepository;
use App\Domain\users\User;

class InMemoryDemandRepository implements DemandRepository
{
    /**
     * @var Demand[]
     */
    private $demands;

    /**
     * InMemoryDemandRepository constructor.
     * @param Demand[] $demands
     */
    public function __construct(array $demands)
    {
        foreach ($demands as $demand) {
            $this->demands[$demand->getUuid()] = $demand;
        }
    }

    /**
     * @return Demand[]
     */
    public function listAllForUser(User $user): array
    {
        $userDemands = [];
        foreach ($this->demands as $demand) {
            foreach ($demand->getLectureSets() as $lectureSet) {
                if ($lectureSet->getLecturer() === $user) {
                    $userDemands[] = $demand;
                }
            }
        }

        return $userDemands;
    }
}