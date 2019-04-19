<?php


namespace Demands\Domain;


use Ramsey\Uuid\Uuid;

class Place
{
    private $uuid;
    private $building;
    private $room;

    public function __construct(int $building, int $room)
    {
        $this->uuid = Uuid::uuid4();
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
