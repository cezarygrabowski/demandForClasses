<?php

namespace Demands\Domain;

class Subject
{
    private $name;
    private $shortName;

    public function __construct(string $name, string $shortenedName)
    {
        $this->name = $name;
        $this->shortName = $shortenedName;
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