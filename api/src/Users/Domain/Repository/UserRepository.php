<?php


namespace Users\Domain\Repository;


use Ramsey\Uuid\UuidInterface;
use Users\Domain\User;

interface UserRepository
{
    public function findByUsername(string $username): ?User;

    public function findByUuid(string $assignorUuid): ?User;

    /**
     * @return User[]
     */
    public function findAllLecturers(): array;
}
