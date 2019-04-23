<?php


namespace Demands\Domain\Repository;


use Demands\Domain\Place;

interface PlaceRepository
{
    public function findOneByBuildingAndRoom(int $building, int $room): ?Place;
    /**
     * @return Place[]
     */
    public function findAll(): array;
}
