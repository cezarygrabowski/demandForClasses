<?php

namespace App\DTO;

class User
{
    public $id;
    public $name;
    public $roles;

    public static function fromUser(\App\Entity\User $user): self
    {
        $dto = new User();

        $dto->id = $user->getId();
        $dto->roles = $user->getRoles()[0];
        $dto->name = $user->getUsername();

        return $dto;
    }
}