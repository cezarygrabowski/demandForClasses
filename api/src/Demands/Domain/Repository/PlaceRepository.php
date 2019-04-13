<?php


namespace Demands\Domain\Repository;


use Demands\Domain\Place;

interface PlaceRepository
{
    public function findOneByBuildingAndRoom(int $building, int $room): ?Place;
}
