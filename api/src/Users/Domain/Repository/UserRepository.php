<?php


namespace Users\Domain\Repository;


use Users\Domain\User;

interface UserRepository
{
    public function findByUsername(string $username): ?User;

    public function findByUuid(string $assignorUuid): ?User;

    /**
     * @return User[]
     */
    public function findAllLecturers(): array;

    /**
     * @return User[]
     */
    public function findAllByQualificationSubjectName(string $subjectName): array;
}
