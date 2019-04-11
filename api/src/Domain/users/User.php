<?php

namespace App\Domain\users;

use Rhumsaa\Uuid\Uuid;

class User
{
    private $username;
    private $uuid;

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->uuid = Uuid::uuid4();
    }

    public function getUuid(): string
    {
        return $this->uuid->toString();
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}