<?php


namespace App\Domain\users\Repository;


use App\Domain\users\User;

interface UserRepository
{
    public function findByUsername(string $username): User;
}