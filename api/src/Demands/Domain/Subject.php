<?php

namespace Demands\Domain;

class Subject
{
    private $name;
    private $shortenedName;

    public function __construct(string $name, string $shortenedName)
    {
        $this->name = $name;
        $this->shortenedName = $shortenedName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortenedName(): string
    {
        return $this->shortenedName;
    }
}