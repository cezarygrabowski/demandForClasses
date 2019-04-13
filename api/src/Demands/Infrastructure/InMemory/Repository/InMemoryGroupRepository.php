<?php


namespace Demands\Infrastructure\InMemory\Repository;



use Demands\Domain\Group;
use Demands\Domain\Repository\GroupRepository;

class InMemoryGroupRepository implements GroupRepository
{
    private $groups;

    /**
     * InMemoryDemandRepository constructor.
     * @param Group[] $groups
     */
    public function __construct(array $groups)
    {
        foreach ($groups as $group) {
            $this->groups[$group->getName()] = $group;
        }
    }

    public function find(string $groupName): ?Group
    {
        return $this->groups[$groupName];
    }
}
