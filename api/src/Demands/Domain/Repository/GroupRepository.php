<?php


namespace Demands\Domain\Repository;


use Demands\Domain\Group;

interface GroupRepository
{
    public function find(string $groupName): ?Group;
}
