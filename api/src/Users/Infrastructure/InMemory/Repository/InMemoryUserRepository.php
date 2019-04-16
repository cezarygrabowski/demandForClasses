<?php


namespace Users\Infrastructure\InMemory\Repository;


use Users\Domain\Repository\UserRepository;
use Users\Domain\User;

class InMemoryUserRepository implements UserRepository
{
    /** @var [] */
    public $users;

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

    public function findByUsername(string $username): ?User
    {
        if(array_key_exists($username, $this->users)) {
            return $this->users[$username];
        }

        return null;
    }

    public function addUser(User $user) {
        $this->users[$user->getUsername()] = $user;
    }

    /**
     * @return User[]
     */
    public function findAllTeachers(): array
    {
        $teachers = [];
//        $this->users
        // TODO: Implement findAllTeachers() method.
    }

    public function findOneByToken(string $apiToken): ?User
    {
        // TODO: Implement findOneByToken() method.
    }
}
