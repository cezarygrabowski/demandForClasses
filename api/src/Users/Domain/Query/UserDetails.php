<?php


namespace Users\Domain\Query;


class UserDetails
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $automaticallySendDemands;

    public static function getUsersDetails(\Users\Domain\User $user)
    {
        $userDetails = new self();

        $userDetails->email = $user->getEmail();
        $userDetails->automaticallySendDemands= $user->isAutomaticallySendDemands();

        return $userDetails;
    }

}