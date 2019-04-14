<?php


namespace Users\Application\Command;


use Users\Domain\Import\ImportUser;
use Users\Domain\User;

class ImportUsers
{
    /**
     * @var User
     */
    private $importedBy;
    /**
     * @var array|ImportUser[]
     */
    private $importUsers;

    /**
     * ImportUsers constructor.
     * @param User $importedBy
     * @param ImportUser[] $importUsers
     */
    public function __construct(User $importedBy, array $importUsers)
    {
        $this->importedBy = $importedBy;
        $this->importUsers = $importUsers;
    }

    /**
     * @return User
     */
    public function getImportedBy(): User
    {
        return $this->importedBy;
    }

    /**
     * @return array|ImportUser[]
     */
    public function getImportUsers()
    {
        return $this->importUsers;
    }
}