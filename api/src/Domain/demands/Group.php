<?php


namespace App\Domain\demands;


class Group
{
    const FULL_TIME_STUDIES = 0;
    const PART_TIME_STUDIES = 1;

    const GROUP_TYPES = [
        self::FULL_TIME_STUDIES => 'Studia stacjonarne',
        self::PART_TIME_STUDIES => 'Studia niestacjonarne'
    ];

    private $name;
    private $type;

    public function __construct(string $name, int $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): int
    {
        return $this->type;
    }
}