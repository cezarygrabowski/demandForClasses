<?php


namespace Demands\Domain;


use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class Group
{
    const FULL_TIME_STUDIES = 0;
    const PART_TIME_STUDIES = 1;

    const GROUP_TYPES_INT_TO_STRING = [
        self::FULL_TIME_STUDIES => 'Studia stacjonarne',
        self::PART_TIME_STUDIES => 'Studia niestacjonarne'
    ];

    const GROUP_TYPE_STRING_TO_INT = [
        'Studia stacjonarne' => self::FULL_TIME_STUDIES,
        'Studia niestacjonarne' => self::PART_TIME_STUDIES
    ];
    private $uuid;
    private $name;
    private $type;
    private $demands;

    public function __construct(string $name, int $type)
    {
        $this->uuid = Uuid::uuid4();
        $this->name = $name;
        $this->type = $type;
        $this->demands = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getTranslatedType(): string
    {
        return self::GROUP_TYPES_INT_TO_STRING[$this->type];
    }
}
