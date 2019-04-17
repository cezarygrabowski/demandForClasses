<?php


namespace Users\Infrastructure\Doctrine\Repository;


use Users\Domain\Repository\UserRepository;
use Users\Domain\User;

class DoctrineUserRepository implements UserRepository
{
    public function findByUsername(string $username): ?User
    {
        // TODO: Implement findByUsername() method.
    }

    /**
     * @return User[]
     */
    public function findAllTeachers(): array
    {
        // TODO: Implement findAllTeachers() method.
    }

    public function findOneByToken(string $apiToken): ?User
    {
        // TODO: Implement findOneByToken() method.
    }

    public function findByUuid(string $assignorUuid): ?User
    {
        // TODO: Implement findByUuid() method.
    }
}
