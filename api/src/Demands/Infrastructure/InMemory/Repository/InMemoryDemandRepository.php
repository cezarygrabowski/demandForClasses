<?php

namespace Demands\Infrastructure\InMemory\Repository;

use Demands\Domain\Demand;
use Demands\Domain\Repository\DemandRepository;
use Users\Domain\User;

class InMemoryDemandRepository implements DemandRepository
{
    /**
     * @var Demand[]
     */
    public $demands;

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
                if ($lectureSet->getLecturer() && $lectureSet->getLecturer()->getUuid() === $user->getUuid()) {
                    $userDemands[] = $demand;
                }
            }
        }

        return $userDemands;
    }

    /**
     * @param array $uuids
     * @return Demand[]
     */
    public function findByIdentifiers(array $uuids): array
    {
        $exportDemands = [];
        foreach ($uuids as $uuid) {
            if (array_key_exists($uuid, $this->demands)) {
                $exportDemands[] = $this->demands[$uuid];
            }
        }

        return $exportDemands;
    }

    /**
     * @param array $statuses
     * @return Demand[]
     */
    public function listAllWithStatuses(array $statuses): array
    {
        $demands = [];
        foreach ($this->demands as $demand) {
            if (in_array($demand->getStatus(), $statuses)) {
                $demands[] = $demand;
            }
        }

        return $demands;
    }

    public function findOneByUuid(string $demandUuid): ?Demand
    {
        if (array_key_exists($demandUuid, $this->demands)) {
            return $this->demands[$demandUuid];
        }

        return null;
    }
}