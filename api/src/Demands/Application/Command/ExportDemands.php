<?php


namespace Demands\Application\Command;


use Demands\Domain\Export\ExportDemandDto;
use Ramsey\Uuid\UuidInterface;
use Users\Domain\User;

class ExportDemands
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var ExportDemandDTO[]
     */
    private $uuids;

    public function __construct(array $uuids, User $user)
    {
        $this->uuids = $uuids;
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return UuidInterface[]
     */
    public function getUuids(): array
    {
        return $this->uuids;
    }
}