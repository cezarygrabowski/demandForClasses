<?php

namespace Demands\Domain;

use Ramsey\Uuid\Uuid;

class Subject
{
    private $uuid;
    private $name;
    private $shortName;

    public function __construct(string $name, string $shortName)
    {
        $this->uuid = Uuid::uuid4();
        $this->name = $name;
        $this->shortName = $shortName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }
}
