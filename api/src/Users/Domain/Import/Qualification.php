<?php


namespace Users\Domain\Import;


class Qualification
{
    public $name;
    public $shortName;

    public function __construct(string $name, string $shortName)
    {
        $this->name = $name;
        $this->shortName = $shortName;
    }
}