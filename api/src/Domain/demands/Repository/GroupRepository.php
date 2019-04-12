<?php


namespace App\Domain\demands\Repository;


use App\Domain\demands\Group;

interface GroupRepository
{
    public function find(string $groupName): ?Group;
}
