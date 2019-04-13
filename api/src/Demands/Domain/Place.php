<?php


namespace Demands\Domain;


class Place
{
    private $building;
    private $room;

    public function __construct(int $building, int $room)
    {
        $this->building = $building;
        $this->room = $room;
    }

    public function getBuilding(): int
    {
        return $this->building;
    }

    public function getRoom(): int
    {
        return $this->room;
    }
}
