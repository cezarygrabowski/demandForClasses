<?php


namespace Users\Application\Command;


use Users\Domain\User;

class UpdateProfile
{
    private $data;

    /**
     * @var User
     */
    private $user;
    /**
     * UpdateProfile constructor.
     * @param $data
     */
    public function __construct(User $user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}