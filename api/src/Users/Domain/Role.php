<?php


namespace Users\Domain;


class Role
{
    const ADMIN = 0;
    const TEACHER = 1;
    const DISTRICT_MANAGER = 2;
    const INSTITUTE_DIRECTOR = 3;
    const DEAN = 4;
    const PLANNER = 5;

    const ROLES_STRING_TO_INT = [
        'Administrator' => 0,
        'Nauczyciel' => 1,
        'Kierownik zakładu' => 2,
        'Dyrektor instytutu' => 3,
        'Dziekan' => 4,
        'Planista' => 5,
    ];

    const ROLES_INT_TO_STRING = [
        0 => 'Administrator',
        1 => 'Nauczyciel',
        2 => 'Kierownik zakładu',
        3 => 'Dyrektor instytutu',
        4 => 'Dziekan',
        5 => 'Planista'
    ];

    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $name;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Role
    {
        $this->user = $user;
        return $this;
    }

    public function getName(): int
    {
        return $this->name;
    }

    public function setName(int $name): Role
    {
        $this->name = $name;
        return $this;
    }
}