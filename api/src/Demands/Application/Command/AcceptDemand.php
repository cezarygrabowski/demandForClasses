<?php


namespace Demands\Application\Command;


use Demands\Domain\Demand;
use Users\Domain\User;

class AcceptDemand
{
    private $demand;
    private $user;

    public function __construct(Demand $demand, User $user)
    {
        $this->demand = $demand;
        $this->user = $user;
    }

    public function getDemand(): Demand
    {
        return $this->demand;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}