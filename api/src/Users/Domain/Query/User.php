<?php


namespace Users\Domain\Query;


class User
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $qualifications;

    /**
     * @var string
     */
    public $roles;

    public $uuid;

    /**
     * @param User[] $lecturers
     * @return array
     */
    public static function fromUsersCollection(array $lecturers)
    {
        $queryLecturers = [];
        foreach ($lecturers as $lecturer) {
            $queryLecturers[] = User::fromUser($lecturer);
        }

        return $queryLecturers;
    }

    public static function fromUser(\Users\Domain\User $user): self
    {
        $lecturer = new self();
        $lecturer->uuid = $user->getUuid();
        $lecturer->username = $user->getUsername();
        $lecturer->roles = $user->getTranslatedRoles();
        $lecturer->qualifications = $user->getQualificationsAsSubjectNames();

        return $lecturer;
    }
}