<?php


namespace Demands\Infrastructure\Doctrine\Repository;


use Demands\Domain\Group;
use Demands\Domain\Repository\GroupRepository;

class DoctrineGroupRepository implements GroupRepository
{
    public function find(string $groupName): ?Group
    {
        // TODO: Implement find() method.
    }
}
