<?php


namespace App\Infrastructure\users\InMemory\Repository;


use App\Domain\users\Repository\UserRepository;
use App\Domain\users\User;

class InMemoryUserRepository implements UserRepository
{
    /** @var [] */
    private $users;

    /**
     * InMemoryUserRepository constructor.
     * @param User[] $users
     */
    public function __construct(array $users)
    {
        foreach ($users as $user) {
            $this->users[$user->getUsername()] = $user;
        }
    }

    public function findByUsername(string $username): User
    {
        return $this->users[$username];
    }
}