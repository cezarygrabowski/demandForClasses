<?php


namespace Users\Domain\Repository;


use Users\Domain\User;

interface UserRepository
{
    public function findByUsername(string $username): ?User;

    /**
     * @return User[]
     */
    public function findAllTeachers(): array;
}